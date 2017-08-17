<?php 
namespace App\Models;

class GoogleFolder {

const FOLDER_MIME_TYPE = 'application/vnd.google-apps.folder';


public function getSubtreeForFolder($parentId, $sort=true, $service)
{
    // $service = $this->createCrmGService();
// dd($service->files);
    // A. folder info
    //$file = $service->files->get($parentId);
    $file = $service->files->listFiles();
dd($file);
    $ret = array(
        'id' => $parentId,
        'name' => $file->getTitle(),
        'description' => $file->getDescription(),
        'mimetype' => $file->getMimeType(),
        'is_folder' => true,
        'children' => array(),
        'node' => $file,
    );

    if ($ret['mimetype'] != self::FOLDER_MIME_TYPE) {
        throw new Exception(_t("{$ret['name']} is not a folder."));
    }

    $items = $this->findAllFiles($queryString='trashed = false', $parentId, $fieldsFilter='items(alternateLink,description,fileSize,id,mimeType,title)', $service); 

    foreach ($items as $child)
    {
        if ($this->isFolder($child)) 
        {
            $ret['children'][] = $this->getSubtreeForFolder($child->id, $sort, $service);
        }

        else
        {
            // B. file info
            $a['id'] = $child->id;
            $a['name'] = $child->title;
            $a['description'] = $child->description;
            $a['is_folder'] = false;
            $a['url'] = $file->getDownloadUrl();
            $a['url_detail'] = $child->getAlternateLink();
            $a['versionLabel'] = false; //FIXME
            $a['node'] = $child;

            if (!$a['versionLabel']) {
                $a['versionLabel'] = '1.0'; //old files compatibility hack
            }
            $ret['children'][] = $a;
        }
    }

    if ($sort && isset($ret['children'])) 
    {
        if ($sort === true) {
            $sort = create_function('$a, $b', 'if ($a[\'name\'] == $b[\'name\']) return 0; return strcasecmp($a[\'name\'], $b[\'name\']);');
        }
        usort($ret['children'], $sort);
    }

    return $ret;
}


public function findAllFiles($queryString, $parentId=false, $fieldsFilter='items(id,title)', $service = false) 
{
    if (!$service)  $service = $this->createCrmGService();

    $result = array();
    $pageToken = NULL;

    if ($parentId) {
        $queryString .= ($queryString ? ' AND ' : '') . "'{$parentId}' in parents";
    }

    do {
        try {

            $parameters = array('q' => $queryString);

            if ($fieldsFilter) $parameters['fields'] = $fieldsFilter;

            if ($pageToken) {
                $parameters['pageToken'] = $pageToken;
            }

            $files = $service->files->listFiles($parameters);

            $result = array_merge($result, $files->getItems());

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