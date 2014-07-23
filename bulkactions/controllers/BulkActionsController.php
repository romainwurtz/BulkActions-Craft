<?php
namespace Craft;

/**
 * Bulk Actions Delete controller
 */
class BulkActionsController extends BaseController
{


    private function sanitizeParamsStatus($status, &$errors = false) {
        if (is_array($status))
        {
            foreach ($status as $key => $val)
            {
                $status[$key] = $this->sanitizeParamsStatus($val, $errors);
            }
        }
        else
        {
            if ($status == '*') {
                $status = null;
            }
            else if (!($status == 'live' || $status == 'pending' || $status == 'expired' || $status == "disabled")) {
                $errors = true;
            }
        }
        return $status;
    }


    private function sanitizeParamsSections($sectionId, &$errors = false) {
        if (is_array($sectionId))
        {
            foreach ($sectionId as $key => $val)
            {
                $sectionId[$key] = $this->sanitizeParamsSections($val);
            }
            $sectionId = array_filter($sectionId);
        }
        else
        {
            if ($sectionId == '*') {
                $sectionId = null;
            }
            else if (!(ctype_digit($sectionId))) {
                $errors = true;
            }
        }
        return $sectionId;
    }

    public function actionDelete()
    {
        $this->requirePostRequest();

        $status = craft()->request->getRequiredPost('status');
        $sections = craft()->request->getRequiredPost('sections');

        $errors = false;
        $status = $this->sanitizeParamsStatus($status, $errors);
        $sections = $this->sanitizeParamsSections($sections, $errors);

        if ($errors != true) {
            $entriesDeleted = craft()->bulkActions->delete($status, $sections);
            if ($entriesDeleted == 0) {
                $resultMessage = Craft::t('No entries have been deleted.');
            } else {
                $resultMessage = $entriesDeleted.' '.Craft::t('entries deleted.');
            }
            craft()->userSession->setNotice($resultMessage);
        } else {
            craft()->userSession->setError(Craft::t('Errors have been found in the submitted form.'));
        }
        craft()->urlManager->setRouteVariables(array('statusSelected' => $status, 'sectionsSelected'=> $sections));
    }



    public function actionMisc()
    {
        $this->requirePostRequest();

        $do = craft()->request->getRequiredPost('do');
        $sections = craft()->request->getRequiredPost('sections');

        $errors = false;
        $sections = $this->sanitizeParamsSections($sections, $errors);

        if ($errors != true) {
            if ($do == "save") {
                $entriesAffected = craft()->bulkActions->save($sections);
            }

            if ($entriesAffected >= 0) {
                if ($entriesAffected == 0) {
                    $resultMessage = Craft::t('No entries have been updated.');
                } else {
                    $resultMessage = $entriesAffected.' '.Craft::t('entries have been updated.');
                }
                craft()->userSession->setNotice($resultMessage);
            } else {
                craft()->userSession->setError(Craft::t('An error occurred.'));
            }
        } else {
            craft()->userSession->setError(Craft::t('Errors have been found in the submitted form.'));
        }
        craft()->urlManager->setRouteVariables(array('sectionsSelected'=> $sections, 'doSelected'=> $do));
    }
}
