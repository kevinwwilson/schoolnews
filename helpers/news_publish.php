<?php

defined('C5_EXECUTE') or die("Access Denied.");

class NewsPublishHelper {

    /**
     * Checks the news groups to see if there is a group that needs to be published
     *
     * @return bool true if there is a group that is ready for publishing
     */
    public function groupReadyToPublish() {
        $db = Loader::db();
        $newsGroups = $db->GetArray("SELECT * FROM `btselectProNewsList` order by time desc");
        $current_time = date("Y-m-d H:i:s");
        foreach ($newsGroups as $group) {
            // as soon as we find a published group, the search is over
            if ($group['active'] == 1) {
                return false;
            }

            if ($group['active'] == 0 && $current_time > $group['time'])  {
                return true;
            }
        }
        return false;
    }

    /**
     * Publishes a news group
     *
     * @return null
     */
    public function publishGroup() {
        $db = Loader::db();
        $newsGroups = $db->GetArray("SELECT * FROM `btselectProNewsList` order by time desc");
        $current_time = date("Y-m-d H:i:s");
        $lesstime = array();
        foreach($newsGroups as $data){
            $curid = $data['ID'];
            $ctime = $data['time'];
                if($current_time > $ctime){
                    array_push($lesstime, $ctime);
                }
        }
        $largevalue = max($lesstime);

        //seems a bit overkill to update the entire table, but whatever
        $sqlz = $db->query("UPDATE btselectProNewsList SET active='0'");
        $db->Execute($sqlz);
        $sql = $db->query("UPDATE btselectProNewsList SET active='1' WHERE time='$largevalue'");
        $db->Execute($sql);

        $rows = $db->GetArray("SELECT * FROM btselectProNewsList WHERE time='$largevalue'");

        foreach($rows as $actdata){
            $curids = $actdata['atID'];
            $idsplit = explode('||', $curids);
            foreach($idsplit as $atids)
            {
                $page = Page::getByID($atids, $version = 'ACTIVE');
                $page->setAttribute('group_status', 'Published');
            }
        }
    }

}
