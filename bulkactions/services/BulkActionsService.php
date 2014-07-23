<?php
namespace Craft;

/**
 * Bulk Actions Delete service
 */
class BulkActionsService extends BaseApplicationComponent
{

    private function findElements($sectionId = null, $status = null) {
        $criteria = craft()->elements->getCriteria('Entry');
        $criteria->limit = null;
        if ($sectionId != null) {
            $criteria->sectionId = $sectionId;
        }
        if ($status != null) {
            $criteria->status = $status;
        }
        $entries = $criteria->find();
        return $entries;
    }

    private function translateEntryModelStatus($type) {
        if (is_array($type))
        {
            foreach ($type as $key => $value)
            {
                $type[$key] = $this->translateEntryModelStatus($value);
            }
        }
        else
        {
            if ($type == 'live') {
                $type = EntryModel::LIVE;
            }
            else if ($type == 'pending') {
                $type = EntryModel::PENDING;
            }
            else if ($type == "expired") {
                $type = EntryModel::EXPIRED;
            }
            else if ($type == "disabled") {
                $type = EntryModel::DISABLED;
            } else {
                $type = null;
            }
        }
        return $type;
    }

    public function delete($status = null, $sectionId = null)
    {
        $status = $this->translateEntryModelStatus($status);
        $findElements = $this->findElements($sectionId, $status);
        $res = craft()->entries->deleteEntry($findElements);
        return $res == true ? count($findElements) : 0;
    }

    public function save($sectionId = null)
    {
        $findElements = $this->findElements($sectionId);
        $res = true;
        foreach($findElements as $entry) {
            $resTmp = craft()->entries->saveEntry($entry);
            if ($res != false) {
                $res = $resTmp;
            }
        }
        return $res == true ? count($findElements) : -1;
    }
}
