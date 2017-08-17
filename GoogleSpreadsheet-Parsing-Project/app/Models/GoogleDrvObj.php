<?php
namespace App\Models;
use App\Models\Exception;
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;
class GoogleDrvObj
{
    private $client_id;
    private $client_secret;
    private $redirect_url;

    const FOLDER_MIME_TYPE = 'application/vnd.google-apps.folder';
    private $service;
    private $treeHTML = '';
    private $treeData = array();
    private $settings_data = array();
    private $status_data = array();
    private $ignore_list = array();
    private $head_arr = array();
    private $anal_ret_data = array();
    private $access_token;

    public function __construct($service=null, $accessToken=null)
    {
        $this->service = $service;
        $this->treeHTML = '';
        $this->access_token = $accessToken;

        $serviceRequest = new DefaultServiceRequest($accessToken);
        ServiceRequestFactory::setInstance($serviceRequest);
    }

    public function setParam( $service, $accessToken )
    {
        $this->service = $service;
        $this->treeHTML = '';

        $serviceRequest = new DefaultServiceRequest($accessToken);
        ServiceRequestFactory::setInstance($serviceRequest);
    }
    public function client()
    {
        $client = new \Google_Client();
        $client->setDeveloperKey(env('GOOGLE_API_KEY'));
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URL'));
        $client->setScopes(env('GOOGLE_SCOPES'));
        $client->setApprovalPrompt(env('GOOGLE_APPROVAL_PROMPT'));
        $client->setAccessType(env('GOOGLE_ACCESS_TYPE'));

        return $client;
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        $client = new \Google_Client();
        $client->setAuthConfig(\Config::get('google'));
        return $client;
    }

    public function drive($client)
    {
        $drive = new \Google_Service_Drive($client);
        return $drive;
    }
    public function directory($client){
        $directory = new \Google_Service_Directory($client);
        return $directory;
    }

    public function setStatusData( $status_data ) {
        $this->status_data = $status_data;
    }

    public function setSettings($settings_data){
        $this->settings_data = $settings_data;
        $this->ignore_list = $settings_data['ignore_list'];
    }

    public function getSubtreeForFolder($parentId, $sort=true, $parent=null)
    {
        $service = $this->service;

        // A. folder info
        $file = $service->files->get($parentId);
        if (is_null($parent))  $parent = '#';
        $ret = array(
            'id' => $parentId,
            'name' => $file->getName(),
            'description' => $file->getDescription(),
            'mimetype' => $file->getMimeType(),
            'is_folder' => true,
            'parent'=>$parent,
            'children' => array(),
            'node' => $file,
        );

        $this->treeData[] = array( 'id'=>$ret['id'],'text'=>$ret['name'],'parent'=>$ret['parent'], 'icon'=>'../../assets/img/gfolder.png', 'state'=>array('opend'=>false, 'disabled'=>false, 'selected'=>false) );

        $this->treeHTML .= "<ul><li>{$ret['name']}";

        if ($ret['mimetype'] != self::FOLDER_MIME_TYPE) {
            throw new Exception(_t("{$ret['name']} is not a folder."));
        }
        $items = $this->findAllFiles($queryString='trashed = false', $parentId, $fieldsFilter='items(alternateLink,description,fileSize,id,mimeType,title)', $service);

        $this->treeHTML .= "<ul>";
        foreach ($items as $child)
        {
            if ($this->isFolder($child))
            {
                $ret['children'][] = $this->getSubtreeForFolder($child->id, $sort, $file->id);
            }

            else
            {
                // B. file info
                if ($child->getMimeType() == 'application/vnd.google-apps.spreadsheet') {
                    $a['id'] = $child->id;
                    $a['name'] = $child->name;
                    $a['description'] = $child->description;
                    $a['mimetype'] = $child->getMimeType();
                    $a['is_folder'] = false;
                    $a['parent'] = $file['id'];
                    $a['versionLabel'] = false; //FIXME
                    $a['node'] = $child;
                    if (!$a['versionLabel']) {
                        $a['versionLabel'] = '1.0'; //old files compatibility hack
                    }
                    $ret['children'][] = $a;

                    $this->treeHTML .= "<li>{$a['name']}</li>";
                    $this->treeData[] = array( 'id'=>$a['id'],'text'=>$a['name'],'parent'=>$a['parent'], 'icon'=>'../../assets/img/spreedsheet.png' );
                }

            }
        }
        $this->treeHTML .= "</ul>";
        if ($sort && isset($ret['children']))
        {
            if ($sort === true) {
                $sort = create_function('$a, $b', 'if ($a[\'name\'] == $b[\'name\']) return 0; return strcasecmp($a[\'name\'], $b[\'name\']);');
            }
            usort($ret['children'], $sort);
        }
        $this->treeHTML .= "</li></ul>";
        return $ret;
    }

    public function getSubtreeForFolderSpreadSheet($parentId, $sort=true, $parent=null)
    {
        $service = $this->service;
        $ret = array();
        $top_name = '';
        // A. folder info
        $file = $service->files->get($parentId);
        if (is_null($parent))  $parent = '#';
        if ( !in_array($file->getName(), $this->ignore_list) ) {
            $ret = array(
                'id' => $parentId,
                'name' => $file->getName(),
                'description' => $file->getDescription(),
                'mimetype' => $file->getMimeType(),
                'is_folder' => true,
                'parent'=>$parent,
                'status'=>'',
                'product' => array(),
            );
            $top_name = $file->getName();
            foreach ($this->settings_data['additional_columns'] as $key => $val) {
                if ($val != '') $ret[$val] = '';
            }
            
            
            // $this->head_arr

            /*
						if ($file->getMimeType() != self::FOLDER_MIME_TYPE) {
                $ret['is_folder'] = false;
                return $ret;
            }*/
            $this->anal_ret_data[] = $ret;
            if ($file->getMimeType() != self::FOLDER_MIME_TYPE) {
							$ret['is_folder'] = false;
							$items = $ret;
							$title = $ret['name'];
							$top_name .= "->".$ret['name'];
							// $sheet_name = $a['name'];
							$spreadsheetService = new \Google\Spreadsheet\SpreadsheetService();
                            $worksheetFeed = $spreadsheetService->getSpreadsheetFeed()->getByTitle($title);
                            $worksheets = $worksheetFeed->getWorksheetFeed()->getEntries();
    //foreach()
                            foreach ($worksheets as $worksheet) {
                                $tmpTitle = str_replace(' ', '', strtolower($worksheet->getTitle()));
                                if (!in_array($tmpTitle, $this->ignore_list)) {

                                    /*****   New Algorithm Start *****/
                                    $sheet_data_arr = $worksheet->getCellFeed()->toArray();

                                    $cnt = count($sheet_data_arr);
                                    $sheetTitle = $worksheet->getTitle();
                                    $sheet_arr = array();
                                    $sheet_arr['id'] = $worksheet->getGid();
                                    $sheet_arr['name'] = $sheetTitle;
                                    $sheet_arr['description'] = $sheetTitle;
                                    $sheet_arr['mimetype'] = 'sheet';
                                    $sheet_arr['is_folder'] = false;
                                    $sheet_arr['parent'] = $ret['id'];
                                    $sheet_arr['status'] = '';

                                    $sheet_name = $top_name."->".$sheetTitle;
    //                                $sheet_arr['versionLabel'] = false;
                                    foreach ($this->settings_data['additional_columns'] as $key => $val) {
                                        if ($val != '') $sheet_arr[$val] = '';
                                    }

                                    $this->anal_ret_data[] = $sheet_arr;

                                    $sep_arr = array();
                                    if ( $this->settings_data['case_sep'] == '' ) {
                                        $sep_arr = $this->getSepDataByBlank($sheet_data_arr);
                                    }
                                    else if ( $this->settings_data['case_sep'] == 'NULL' ) {
                                        $sep_arr = array();
                                    }
                                    else {
                                        $sep_arr = $this->getSepArr($sheet_data_arr);
    // dd($sheet_data_arr);                                    
                                    }
    // dd($sep_arr);                                
                                    if (count($sep_arr)>1) {
                                        $step = 0;
                                        if ( $this->settings_data['fb_cell'][0] < 0 ) {
                                            $arr = array_keys($sheet_data_arr);
                                            // dd($sep_arr);
                                            for( $i=0; $i<count($sep_arr);$i++ ) {
                                                ( isset($sep_arr[$i+1]) ) ? $next = $sep_arr[$i+1]+intval($this->settings_data['fb_cell']) : $next = end($arr);
                                                $_pos = $i+intval($this->settings_data['fb_cell']);
                                                $label = $sheet_data_arr[$_pos][$this->settings_data['script_cell']['col']];
                                                
                                                foreach( $this->settings_data['additional_columns'] as $column ) {
                                                    if (array_key_exists($column, $sheet_data_arr[$_pos])) $script_arr[$column] = $sheet_data_arr[$_pos][$column];
                                                }
                                                $status_arr = array();
                                                $start_pos = $i+intval($this->settings_data['status_row_start']);
                                                for( $no = $start_pos; $no<$next; $no++ ) {
                                                    if (array_key_exists($this->settings_data['status_cell'], $sheet_data_arr[$no])) 
                                                        $status_arr[] = $sheet_data_arr[$no][$this->settings_data['status_cell']];
                                                }
                                                $status_result = $this->getStatusResult($status_arr);
                                                if ( $status_result == 'NULL' ) $status_result = implode(',', $status_arr);
                                                $script_arr['id'] = $worksheet->getGid() . '_test_script' . $start_pos;
                                                $script_arr['name'] = $label;
                                                $script_arr['decsription'] = $label;
                                                $script_arr['mimetype'] = 'script';
                                                $script_arr['status'] = $status_result;
                                                $script_arr['is_folder'] = false;
                                                $script_arr['parent'] = $sheet_arr['id'];
                                                $script_arr['sheet_name'] = $sheet_name;
                                                $script_name = $sheet_name. "->".$script_arr['name'];
                                                $script_arr['treename'] = $script_name;
                                                $sheet_arr['product'][] = $script_arr;
                                                $this->anal_ret_data[] = $script_arr;
                                            }
                                        }
                                        else {
                                            ( $this->settings_data['fb_cell'][0]<0 ) ? $step = $this->settings_data['status_row_start'] : $step = $this->settings_data['fb_cell'][0];
                                            $start_pos = $this->settings_data['script_cell']['row'];
                                            $script_arr = array();
                                            while(count($sep_arr)>0) {
                                            
                                                $label = $sheet_data_arr[$start_pos][$this->settings_data['script_cell']['col']];
                                                foreach( $this->settings_data['additional_columns'] as $column ) {
                                                    if (array_key_exists($column, $sheet_data_arr[$start_pos])) $script_arr[$column] = $sheet_data_arr[$start_pos][$column];
                                                }
                                                $status_arr = array();
                                                $sepValue = reset($sep_arr);
                                                for( $no = $start_pos; $no <= ($sepValue - $step); $no++ ) {
                                                    if (array_key_exists($this->settings_data['status_cell'], $sheet_data_arr[$no])) 
                                                        $status_arr[] = $sheet_data_arr[$no][$this->settings_data['status_cell']];
                                                }

                                                $status_result = $this->getStatusResult($status_arr);
                                                if ( $status_result == 'NULL' ) $status_result = implode(',', $status_arr);
                                                $script_arr['id'] = $worksheet->getGid() . '_test_script' . $start_pos;
                                                $script_arr['name'] = $label;
                                                $script_arr['decsription'] = $label;
                                                $script_arr['mimetype'] = 'script';
                                                $script_arr['status'] = $status_result;
                                                $script_arr['is_folder'] = false;
                                                $script_arr['parent'] = $sheet_arr['id'];
                                                $script_arr['sheet_name'] = $sheet_name;
                                                $script_name = $sheet_name. "->".$script_arr['name'];
                                                $script_arr['treename'] = $script_name;
                                                $sheet_arr['product'][] = $script_arr;
                                                $this->anal_ret_data[] = $script_arr;

                                                $start_pos = $sepValue + intval($step);
                                                array_shift($sep_arr);
                                            }
                                            $label = $sheet_data_arr[$start_pos][$this->settings_data['script_cell']['col']];
                                            foreach( $this->settings_data['additional_columns'] as $column ) {
                                                if (array_key_exists($column, $sheet_data_arr[$start_pos])) $script_arr[$column] = $sheet_data_arr[$start_pos][$column];
                                            }
                                            $status_arr = array();
                                            $sepValue = reset($sep_arr);
                                            for( $no = $start_pos; $no <= ($sepValue - $step); $no++ ) {
                                                if (array_key_exists($this->settings_data['status_cell'], $sheet_data_arr[$no])) 
                                                    $status_arr[] = $sheet_data_arr[$no][$this->settings_data['status_cell']];
                                            }

                                            $status_result = $this->getStatusResult($status_arr);
                                            if ( $status_result == 'NULL' ) $status_result = implode(',', $status_arr);
                                            $script_arr['id'] = $worksheet->getGid() . '_test_script' . $start_pos;
                                            $script_arr['name'] = $label;
                                            $script_arr['decsription'] = $label;
                                            $script_arr['mimetype'] = 'script';
                                            $script_arr['status'] = $status_result;
                                            $script_arr['is_folder'] = false;
                                            $script_arr['parent'] = $sheet_arr['id'];
                                            $script_arr['sheet_name'] = $sheet_name;
                                            $script_name = $sheet_name. "->".$script_arr['name'];
                                            $script_arr['treename'] = $script_name;
                                            $sheet_arr['product'][] = $script_arr;
                                            $this->anal_ret_data[] = $script_arr;

                                            $start_pos = $sepValue + intval($step);
                                            array_shift($sep_arr);
                                        }
                                        
                                    }
                                    else {
                                        $step = 0;
                                        ( $this->settings_data['fb_cell'][0]<0 ) ? $step = $this->settings_data['status_row_start'] : $step = $this->settings_data['fb_cell'][0];
                                        $start_pos = $this->settings_data['script_cell']['row'];
                                        $arr = array_keys($sheet_data_arr);
                                        $last = end($arr);
                                        for($i=$start_pos; $i<$last; $i++) {
                                            $script_arr = array();
                                            $label = $sheet_data_arr[$start_pos][$this->settings_data['script_cell']['col']];
                                            foreach( $this->settings_data['additional_columns'] as $column ) {
                                            if (array_key_exists($column, $sheet_data_arr[$start_pos])) $script_arr[$column] = $sheet_data_arr[$start_pos][$column];
                                            }
                                            $status_arr = array();
                                            if (array_key_exists($this->settings_data['status_cell'], $sheet_data_arr[$i]))
                                                $status_arr[] = $sheet_data_arr[$i][$this->settings_data['status_cell']];
                                            $status_result = $this->getStatusResult($status_arr);
                                            if ( $status_result == 'NULL' ) $status_result = implode(',', $status_arr);
                                            $script_arr['id'] = $worksheet->getGid() . '_test_script' . $i;
                                            $script_arr['name'] = $label;
                                            $script_arr['decsription'] = $label;
                                            $script_arr['mimetype'] = 'script';
                                            $script_arr['status'] = $status_result;
                                            $script_arr['is_folder'] = false;
                                            $script_arr['parent'] = $sheet_arr['id'];
                                            $script_arr['sheet_name'] = $sheet_name;
                                            $script_name = $sheet_name. "->".$script_arr['name'];
                                            $script_arr['treename'] = $script_name;
                                            $sheet_arr['product'][] = $script_arr;
                                            $this->anal_ret_data[] = $script_arr;

                                            $start_pos += intval($step);
                                            array_shift($sep_arr);
                                        }
                                    }
                                }

                            }
							
								
							
							return $this->anal_ret_data;
						}
						else {
							$items = $this->findAllFiles($queryString='trashed = false', $parentId, $fieldsFilter='items(alternateLink,description,fileSize,id,mimeType,title)', $service);
						}
            foreach ($items as $child)
            {
                if ($this->isFolder($child) )
                {
                    //if (!in_array($child->name, $this->ignore_list)) $ret['product'][] = $this->getSubtreeForFolderSpreadSheet($child->id, $sort, $file->id);
                    if (!in_array($child->name, $this->ignore_list)) $this->anal_ret_data[] = $this->getSubtreeForFolderSpreadSheet($child->id, $sort, $file->id);
                }

                else
                {
                    // B. file info
                    if ($child->getMimeType() == 'application/vnd.google-apps.spreadsheet' && !in_array($child->name, $this->ignore_list)) {
                        $a['id'] = $child->id;
                        $a['name'] = $child->name;
                        $a['description'] = $child->description;
                        $a['mimetype'] = $child->getMimeType();
                        $a['is_folder'] = false;
                        $a['parent'] = $file['id'];
                        $a['status'] = '';
                        $a['versionLabel'] = false; //FIXME
                        foreach ($this->settings_data['additional_columns'] as $key => $val) {
                            if ($val != '') $a[$val] = '';
                        }
//                        if (!$a['versionLabel']) {
//                            $a['versionLabel'] = '1.0'; //old files compatibility hack
//                        }
//                        $ret['children'] = $a;
                        $this->anal_ret_data[]= $a;
                        $title = $a['name'];
                        $top_name .= "->".$a['name'];
                        // $sheet_name = $a['name'];



                        $spreadsheetService = new \Google\Spreadsheet\SpreadsheetService();
                        $worksheetFeed = $spreadsheetService->getSpreadsheetFeed()->getByTitle($title);
                        $worksheets = $worksheetFeed->getWorksheetFeed()->getEntries();
//foreach()
                        // dd(count($worksheets));
                        foreach ($worksheets as $worksheet) {
                            $tmpTitle = str_replace(' ', '', strtolower($worksheet->getTitle()));
                            if (!in_array($tmpTitle, $this->ignore_list)) {

                                /*****   New Algorithm Start *****/
                                $sheet_data_arr = $worksheet->getCellFeed()->toArray();
dd($this->settings_data);
                                if (count($this->head_arr) == 0) {
                                    $r1 = $this->settings_data['script_cell']['row'] - $this->settings_data['fb_cell'][0];
                                    $this->head_arr[0] = "Script Name";
                                    $this->head_arr[1] = "Status";
                                    $pos = 2;
                                    foreach ($this->settings_data['additional_columns'] as $key => $val) {
                                        if ($val != '') $this->head_arr[$pos] = $sheet_data_arr[$r1][$val*1];
                                        $pos = $pos + 1;
                                    }
                                }
                                $cnt = count($sheet_data_arr);
                                $sheetTitle = $worksheet->getTitle();
                                $sheet_arr = array();
                                $sheet_arr['id'] = $worksheet->getGid();
                                $sheet_arr['name'] = $sheetTitle;
                                $sheet_arr['description'] = $sheetTitle;
                                $sheet_arr['mimetype'] = 'sheet';
                                $sheet_arr['is_folder'] = false;
                                $sheet_arr['parent'] = $a['id'];
                                $sheet_arr['status'] = '';

                                $sheet_name = $top_name."->".$sheetTitle;
//                                $sheet_arr['versionLabel'] = false;
                                foreach ($this->settings_data['additional_columns'] as $key => $val) {
                                    if ($val != '') $sheet_arr[$val] = '';
                                }

                                $this->anal_ret_data[] = $sheet_arr;

                                $sep_arr = array();
                                if ( $this->settings_data['case_sep'] == '' ) {
                                    $sep_arr = $this->getSepDataByBlank($sheet_data_arr);
                                }
                                else if ( $this->settings_data['case_sep'] == 'NULL' ) {
                                    $sep_arr = array();
                                }
                                else {
                                    $sep_arr = $this->getSepArr($sheet_data_arr);
// dd($sheet_data_arr);                                    
                                }
// dd($sep_arr);                                
                                if (count($sep_arr)>1) {
                                    $step = 0;
                                    if ( $this->settings_data['fb_cell'][0] < 0 ) {
                                        $arr = array_keys($sheet_data_arr);
                                        // dd($sep_arr);
                                        for( $i=0; $i<count($sep_arr);$i++ ) {
                                            ( isset($sep_arr[$i+1]) ) ? $next = $sep_arr[$i+1]+intval($this->settings_data['fb_cell']) : $next = end($arr);
                                            $_pos = $i+intval($this->settings_data['fb_cell']);
                                            $label = $sheet_data_arr[$_pos][$this->settings_data['script_cell']['col']];
                                            
                                            foreach( $this->settings_data['additional_columns'] as $column ) {
                                                if (array_key_exists($column, $sheet_data_arr[$_pos])) $script_arr[$column] = $sheet_data_arr[$_pos][$column];
                                            }
                                            $status_arr = array();
                                            $start_pos = $i+intval($this->settings_data['status_row_start']);
                                            for( $no = $start_pos; $no<$next; $no++ ) {
                                                if (array_key_exists($this->settings_data['status_cell'], $sheet_data_arr[$no])) 
                                                    $status_arr[] = $sheet_data_arr[$no][$this->settings_data['status_cell']];
                                            }
                                            $status_result = $this->getStatusResult($status_arr);
                                            if ( $status_result == 'NULL' ) $status_result = implode(',', $status_arr);
                                            $script_arr['id'] = $worksheet->getGid() . '_test_script' . $start_pos;
                                            $script_arr['name'] = $label;
                                            $script_arr['decsription'] = $label;
                                            $script_arr['mimetype'] = 'script';
                                            $script_arr['status'] = $status_result;
                                            $script_arr['is_folder'] = false;
                                            $script_arr['parent'] = $sheet_arr['id'];
                                            $script_arr['sheet_name'] = $sheet_name;
                                            $script_name = $sheet_name. "->".$script_arr['name'];
                                            $script_arr['treename'] = $script_name;
                                            $sheet_arr['product'][] = $script_arr;
                                            $this->anal_ret_data[] = $script_arr;
                                        }
                                    }
                                    else {
                                        ( $this->settings_data['fb_cell'][0]<0 ) ? $step = $this->settings_data['status_row_start'] : $step = $this->settings_data['fb_cell'][0];
                                        $start_pos = $this->settings_data['script_cell']['row'];
                                        $script_arr = array();
                                        while(count($sep_arr)>0) {
                                        
                                            $label = $sheet_data_arr[$start_pos][$this->settings_data['script_cell']['col']];
                                            foreach( $this->settings_data['additional_columns'] as $column ) {
                                                if (array_key_exists($column, $sheet_data_arr[$start_pos])) $script_arr[$column] = $sheet_data_arr[$start_pos][$column];
                                            }
                                            $status_arr = array();
                                            $sepValue = reset($sep_arr);
                                            for( $no = $start_pos; $no <= ($sepValue - $step); $no++ ) {
                                                if (array_key_exists($this->settings_data['status_cell'], $sheet_data_arr[$no])) 
                                                    $status_arr[] = $sheet_data_arr[$no][$this->settings_data['status_cell']];
                                            }

                                            $status_result = $this->getStatusResult($status_arr);
                                            if ( $status_result == 'NULL' ) $status_result = implode(',', $status_arr);
                                            $script_arr['id'] = $worksheet->getGid() . '_test_script' . $start_pos;
                                            $script_arr['name'] = $label;
                                            $script_arr['decsription'] = $label;
                                            $script_arr['mimetype'] = 'script';
                                            $script_arr['status'] = $status_result;
                                            $script_arr['is_folder'] = false;
                                            $script_arr['parent'] = $sheet_arr['id'];
                                            $script_arr['sheet_name'] = $sheet_name;
                                            $script_name = $sheet_name. "->".$script_arr['name'];
                                            $script_arr['treename'] = $script_name;
                                            $sheet_arr['product'][] = $script_arr;
                                            $this->anal_ret_data[] = $script_arr;

                                            $start_pos = $sepValue + intval($step);
                                            array_shift($sep_arr);
                                        }
                                        $label = $sheet_data_arr[$start_pos][$this->settings_data['script_cell']['col']];
                                        foreach( $this->settings_data['additional_columns'] as $column ) {
                                            if (array_key_exists($column, $sheet_data_arr[$start_pos])) $script_arr[$column] = $sheet_data_arr[$start_pos][$column];
                                        }
                                        $status_arr = array();
                                        $sepValue = reset($sep_arr);
                                        for( $no = $start_pos; $no <= ($sepValue - $step); $no++ ) {
                                            if (array_key_exists($this->settings_data['status_cell'], $sheet_data_arr[$no])) 
                                                $status_arr[] = $sheet_data_arr[$no][$this->settings_data['status_cell']];
                                        }

                                        $status_result = $this->getStatusResult($status_arr);
                                        if ( $status_result == 'NULL' ) $status_result = implode(',', $status_arr);
                                        $script_arr['id'] = $worksheet->getGid() . '_test_script' . $start_pos;
                                        $script_arr['name'] = $label;
                                        $script_arr['decsription'] = $label;
                                        $script_arr['mimetype'] = 'script';
                                        $script_arr['status'] = $status_result;
                                        $script_arr['is_folder'] = false;
                                        $script_arr['parent'] = $sheet_arr['id'];
                                        $script_arr['sheet_name'] = $sheet_name;
                                        $script_name = $sheet_name. "->".$script_arr['name'];
                                        $script_arr['treename'] = $script_name;
                                        $sheet_arr['product'][] = $script_arr;
                                        $this->anal_ret_data[] = $script_arr;

                                        $start_pos = $sepValue + intval($step);
                                        array_shift($sep_arr);
                                    }
                                    
                                }
                                else {
                                    $step = 0;
                                    ( $this->settings_data['fb_cell'][0]<0 ) ? $step = $this->settings_data['status_row_start'] : $step = $this->settings_data['fb_cell'][0];
                                    $start_pos = $this->settings_data['script_cell']['row'];
                                    $arr = array_keys($sheet_data_arr);
                                    $last = end($arr);
                                    for($i=$start_pos; $i<$last; $i++) {
                                        $script_arr = array();
                                        $label = $sheet_data_arr[$start_pos][$this->settings_data['script_cell']['col']];
                                        foreach( $this->settings_data['additional_columns'] as $column ) {
                                           if (array_key_exists($column, $sheet_data_arr[$start_pos])) $script_arr[$column] = $sheet_data_arr[$start_pos][$column];
                                        }
                                        $status_arr = array();
                                        if (array_key_exists($this->settings_data['status_cell'], $sheet_data_arr[$i]))
                                            $status_arr[] = $sheet_data_arr[$i][$this->settings_data['status_cell']];
                                        $status_result = $this->getStatusResult($status_arr);
                                        if ( $status_result == 'NULL' ) $status_result = implode(',', $status_arr);
                                        $script_arr['id'] = $worksheet->getGid() . '_test_script' . $i;
                                        $script_arr['name'] = $label;
                                        $script_arr['decsription'] = $label;
                                        $script_arr['mimetype'] = 'script';
                                        $script_arr['status'] = $status_result;
                                        $script_arr['is_folder'] = false;
                                        $script_arr['parent'] = $sheet_arr['id'];
                                        $script_arr['sheet_name'] = $sheet_name;
                                        $script_name = $sheet_name. "->".$script_arr['name'];
                                        $script_arr['treename'] = $script_name;
                                        $sheet_arr['product'][] = $script_arr;
                                        $this->anal_ret_data[] = $script_arr;

                                        $start_pos += intval($step);
                                        array_shift($sep_arr);
                                    }
                                }
                            }

                        }
                    }
                }
            }
            if ($sort && isset($ret['product']))
            {
                if ($sort === true) {
                    $sort = create_function('$a, $b', 'if ($a[\'name\'] == $b[\'name\']) return 0; return strcasecmp($a[\'name\'], $b[\'name\']);');
                }
                usort($ret['product'], $sort);
            }
        }

        return $this->anal_ret_data;
    }
    private function getSepDataByBlank( $sheet_arr ) {
        $start_row = $this->settings_data['script_cell']['row'];
        $ret_arr = array();
        $arr = array_keys($sheet_arr);
        for( $i = $start_row; $i<=end($arr); $i++ ) {
            if ( !array_key_exists($i, $sheet_arr) ) $ret_arr[] = $i;
        }
        return $ret_arr;
    }
    private function getSepArr( $sheet_arr ){
        $start_row = $this->settings_data['script_cell']['row'];
        $ret_arr = array();
        $arr = array_keys($sheet_arr);
// dd($start_row, key(end($sheet_arr)));        
        for( $i = $start_row; $i<=end($arr); $i++ ) {
            if ( $this->isSeparateRow($sheet_arr[$i]) ) $ret_arr[] = $i;
        }
        return $ret_arr;
    }
    private function splitArray( $data_arr ) {
        $start_row = $this->settings_data['script_cell']['row'];
        $ret_arr = array();
//        while($start_row<=count($data_arr)){
//
//        }
    }
    public function getHead(){
        return $this->head_arr;
    }
    private function getStatusResult( $status_arr ) {
        if ( count($status_arr) == 1 && $status_arr[0] == "''" ) return 'Not Started';
        $ret = array();
        for( $i=0;$i<count($status_arr ); $i++ ) {
            $status = $status_arr[$i];
            foreach( $this->status_data as $key=>$sData ) {
                $splitData = explode(',', $sData);
                foreach( $splitData as $str ){
                    if (stripos(strtolower(trim($status)), strtolower(trim($str)))!==false)  {
                        $ret[$this->findIndexOfKey($key, $this->status_data)] = $key;
                    }
                }
								
            }
        }
        $retStr = 'Not Started';
        if ( count($ret)>0 )  $retStr = $ret[$this->getFirstElement($ret)];
        return $retStr;
    }
    private function getFirstElement($arr) {
        $idx = 65536;
        foreach( $arr as $key=>$val ){
            if ($idx>$key) $idx = $key;
        }
        if ( $idx == 65536 ) {
            //dd($arr);
            $atm = array_keys($arr);
            $idx = end($atm);
        }
        return $idx;
    }
    private function findIndexOfKey($key_to_index,$array){
        return array_search($key_to_index,array_keys($array));
    }
    private function isSeparateRow( $row ){
        // if ( $this->settings_data['case_sep'] == '' ) {
        //     foreach( $row as $r ) {
        //         if ( $r != '' ) return false ;
        //     }
        //     return true;
        // }
        // else{
            foreach( $row as $r ) {
                if ( strtolower($r) != strtolower($this->settings_data['case_sep']) )
                    continue;
                else
                    return true ;
            }
        // }
        return false;
    }
    /**
     * @return mixed
     */
    public function getTreeHTML()
    {
        return $this->treeHTML;
    }

    /**
     * @return mixed
     */
    public function getTreeData()
    {
        return $this->treeData;
    }
    public function findAllFiles($queryString, $parentId=false, $fieldsFilter='items(id,title)', $service = false)
    {
        if (!$service)  $service = $this->service;

        $result = array();
        $pageToken = NULL;

        if ($parentId) {
            $queryString .= ($queryString ? ' AND ' : '') . "'{$parentId}' in parents";
        }
        do {
            try {

                $parameters = array('q' => $queryString, 'orderBy'=>'folder asc');

//                if ($fieldsFilter) $parameters['fields'] = $fieldsFilter;

                if ($pageToken) {
                    $parameters['pageToken'] = $pageToken;
                }
//                dd($parameters);
                $files = $service->files->listFiles($parameters);

                $result = array_merge($result, $files->getFiles());

                $pageToken = $files->getNextPageToken();

            } catch (Exception $e) {
                print "An error occurred: " . $e->getMessage();
                $pageToken = NULL;
            }
        } while ($pageToken);

        return $result;
    }


    /**
     * @param Google_DriveFile $file
     * @return boolean, jestli je $file slozka.
     */
    protected function isFolder($file)
    {
        return $file->getMimeType() == self::FOLDER_MIME_TYPE;
    }


}