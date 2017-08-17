<?php
namespace App\Models;
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;

class GdriveCls {
    const FOLDER_MIME_TYPE = 'application/vnd.google-apps.folder';
    private $service;
    private $treeHTML = '';
    private $treeData = array();
    private $settings_data = array();

    public function __construct( $service, $accessToken )
    {
        $this->service = $service;
        $this->treeHTML = '';

        $serviceRequest = new DefaultServiceRequest($accessToken);
        ServiceRequestFactory::setInstance($serviceRequest);
    }
    public function setSettings($settings_data){
        $this->settings_data = $settings_data;
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
//dd($child);
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

        // A. folder info
        $file = $service->files->get($parentId);
        if (is_null($parent))  $parent = '#';
        $ret[] = array(
            'id' => $parentId,
            'name' => $file->getName(),
            'description' => $file->getDescription(),
            'mimetype' => $file->getMimeType(),
            'is_folder' => true,
            'parent'=>$parent,
            'children' => array(),
//            'node' => $file,
        );

        if ($ret[0]['mimetype'] != self::FOLDER_MIME_TYPE) {
            throw new Exception(_t("{$ret[0]['name']} is not a folder."));
        }
        $items = $this->findAllFiles($queryString='trashed = false', $parentId, $fieldsFilter='items(alternateLink,description,fileSize,id,mimeType,title)', $service);

        $this->treeHTML .= "<ul>";
        foreach ($items as $child)
        {
            if ($this->isFolder($child))
            {
                $ret[] = $this->getSubtreeForFolder($child->id, $sort, $file->id);
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
//                    $a['node'] = $child;
                    if (!$a['versionLabel']) {
                        $a['versionLabel'] = '1.0'; //old files compatibility hack
                    }
                    $ret[] = $a;
                    $title = $a['name'];

                    $spreadsheetService = new \Google\Spreadsheet\SpreadsheetService();
                    $worksheetFeed = $spreadsheetService->getSpreadsheetFeed()->getByTitle($title);
                    $worksheets = $worksheetFeed->getWorksheetFeed()->getEntries();
                    foreach ($worksheets as $worksheet) {
                        $sheetCSVdata = $worksheet->getCsv();
                        $sheetTitle = $worksheet->getTitle();
                        $csv_data_arr = str_getcsv($sheetCSVdata, "\n");
                        $sheet_arr = array();
                        $sheet_arr['id'] = $worksheet->getGid();
                        $sheet_arr['name'] = $sheetTitle;
                        $sheet_arr['description'] = $sheetTitle;
                        $sheet_arr['mimetype'] = 'sheet';
                        $sheet_arr['is_folder'] = false;
                        $sheet_arr['parent'] = $a['id'];
                        $sheet_arr['versionLabel'] = false;

                        $ret[] = $sheet_arr;
                        $row_data_arr = array();
                        foreach( $csv_data_arr as $csv_row_data )  $row_data_arr[] = str_getcsv($csv_row_data);
                        $row = $this->settings_data['script_cell']['row'];
                        while( $row < (count($row_data_arr)-1) ){
//                            var_dump($row."-".$row_data_arr[$row][$this->settings_data['script_cell']['col']]);
                            if ($row_data_arr[$row][$this->settings_data['script_cell']['col']]!='') {
                                $script_arr['id'] = $worksheet->getGid().'_test_script' . $row;
                                $script_arr['name'] = $row_data_arr[$row][$this->settings_data['script_cell']['col']];
                                $script_arr['decsription'] = $row_data_arr[$row][$this->settings_data['script_cell']['col']];
                                $script_arr['mimetype'] = 'script';
                                $script_arr['is_folder'] = false;
                                $script_arr['parent'] = $sheet_arr['id'];
                                $script_arr['versionLabel'] = false;
                                foreach ($this->settings_data['additional_column'] as $key => $val) {
                                    if ($key!='' && $val!='' && isset($row_data_arr[$row][$val]) ) $script_arr[$key] = $row_data_arr[$row][$val];
                                }
//                            $script_arr[]
                                $ret[] = $script_arr;
//                                $sheet_arr['children'][] = $script_arr;
                            }
                            $row++;
                        }

//                        $a['children'][] = $sheet_arr;
                    }


//                    $ret['children'][] = $a;

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
    private function isSeperateRow(  ){

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