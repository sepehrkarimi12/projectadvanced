<?php

namespace app\modules\secretariat_v2\models;

use app\models\CodeGenerator;
use app\models\Intldate;
use app\models\MessageHandler;
use app\modules\manage\models\User;
use app\modules\manage\models\UserSignature;
use app\modules\secretariat\models\Secretariat;
use app\modules\secretariat\models\SecretariatPeople;
use Exception;
use kartik\mpdf\Pdf;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class OfficeTools
{

    /**
     * @param integer $id
     * @return array
     * @author Mehran
     */
    /*public function setIsSeen($office_id) {
        $secretariat_user = OfficeAssign::find()
            ->where(['office_id' => $office_id])
            ->andWhere(['user_id' => Yii::$app->user->identity->id])
            ->all();

        foreach ($secretariat_user as $data){
            if($data->is_seen != 1){
                $data->is_seen = 1;
                if ($data->save()) {
                    return true;
                } else {
                    return false;
                }
            }
            return true;
        }
    }*/

    /**
     * @param $office_id
     * @return OfficeMain|null
     * @throws \yii\base\Exception
     * @author Mehran
     */
//    public function checkLetterInfo($office_id) {
//        $office_main = OfficeMain::findOne($office_id);
//        if (!OfficeMain::find()->where(['id' => $office_id])->exists()) {
//            Throw new \Exception("Letter not found!");
//        }
//        /**
//         * check if user have permission or the letter is assigned to him
//         */
//        $view_guard = OfficeAssign::findOne(['office_id' => $office_id, 'user_id' => Yii::$app->user->identity->id]);
//        if ($view_guard == null && !Yii::$app->user->can('SecretariatListLettersPerm')){
//            MessageHandler::ShowMessage('شما اجازه دسترسی به این نامه را ندارید ', 'error');
//            return $this->redirect('secretariat_v2/secretariat/list-letters');
//        }
//
//        if ($office_main->status_id == "pending") {
//            MessageHandler::ShowMessage('نامه هنوز تایید نشده است.', 'error');
//            return $this->redirect('secretariat_v2/secretariat/list-letters');
//        }
//
//        return $office_main;
//    }

    /**
     * @param $office_id
     * @return array
     * @author Mehran
     */
    public function letterAssign($office_id) {
        $assign = OfficeAssign::find()
            ->where(['office_id' => $office_id])
            ->andWhere(['user_id' => Yii::$app->user->identity->id])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        $assign_logs_query = OfficeAssign::find()
            ->where(['office_id' => $office_id])
            ->all();

        $assign_logs = [];
        foreach ($assign_logs_query as $key => $assign_log) {
            $assign_log->created_at = Intldate::get()->timestampToPersian($assign_log->created_at);
            $assign_logs[] = $assign_log;
        }

        $assign_tree = [];

        if ($assign != null) {
            $assign_tree['childs'] = [];
            foreach ($assign->childNodes as $childNode) {
                if ($childNode->secretariat_id == $office_id) {
                    $childNode->created_at = Intldate::get()->timestampToPersian($childNode->created_at);
                    $assign_tree['childs'][] = $childNode;
                }
            }

            // set assigns array
            $assign->created_at = Intldate::get()->timestampToPersian($assign->created_at);
            $assign_tree['assigns'][] = $assign;

            while ($assign->parentNode != null){
                $assign = $assign->parentNode;
                $assign->created_at = Intldate::get()->timestampToPersian($assign->created_at);
                $assign_tree['assigns'][] = $assign;
            }

            $assign_tree['assigns'] = array_reverse($assign_tree['assigns']);
            // end assigns array
        }

        $assignment = array(
          'assign_tree' => $assign_tree,
          'assign_logs' => $assign_logs,
        );

        return $assignment;
    }

    /**
     * @param $office_id
     * @return OfficeTranscript[]|null
     * @author Mehran
     */
    public function letterTranscripts($office_id, $request_id = false)
    {
        $transcripts = null;

        $transcripts = OfficeTranscript::find()
            ->Where(['office_id' => $office_id])
            ->all();
        if ($transcripts != null) {
            if ($request_id) {
                foreach ($transcripts as $key => $value) {
                    $data[] = $value->officePeople->id;
                }
            } else {
                foreach ($transcripts as $key => $value) {
                    $data[] = $value->officePeople->name . ' ' . $value->officePeople->family . ' ' . $value->officePeople->occuaption_name . ' ' . $value->officePeople->unit_name . ' ' . $value->officePeople->company_name;
                }
            }
            $transcripts = $data;
        } else {
            $transcripts = null;
        }

        return $transcripts;
    }

    /**
     * @param $office_id
     * @return null|string
     * @author Mehran
     */
    public function letterCategory($office_id)
    {
        $result = null;

        $office_main = OfficeMain::findOne($office_id);
        if($office_main->category == null){
            $result = 'جدید';
        } else {
            if ($office_main->category->name != 'جدید' && $office_main->category->name != null && $relation_id = $office_main->officeRelations->office_relation_id != null) {
                $result = $office_main->category->name . '  به نامه ' . $office_main->archive_number;
            } else {
                $result = $office_main->category->name;
            }
        }

        return $result;
    }

    /**
     * @param $office_id
     * @return null|string
     * @author Mehran
     */
    /*public function findRelationNumber($office_id)
    {
        $relation_number = null;

        $office_main = OfficeMain::findOne($office_id);

        if ($office_main->category->name != 'جدید' && $relation_id = $office_main->officeRelations->office_relation_id != null )
        {
            if (isset($office_main->officeRelations) && $office_main->officeRelations != null) {
                if ($relation_id = $office_main->officeRelations->office_relation_id != null )
                {
                    $relation_number = $office_main->archive_number;
                }
            }
        }
        return $relation_number;
    }*/

    /**
     * @param $attachment_id
     * @param $office_id
     * @return mixed
     * @throws \Throwable
     * @throws \yii\base\Exception
     * @throws \yii\db\StaleObjectException
     * @author Mehran
     */
    public function deleteAttachment($attachment_id, $office_id)
    {
        $selected_attachment = OfficeAttachment::findOne($attachment_id);

        if ($selected_attachment->delete()){
            MessageHandler::ShowMessage('با موفقیت حذف شد', 'success');
            return $this->redirect('/secretariat/secretariat/update-letter?id=' . $office_id);
        }
    }

    /**
     * @param model $office_main
     * @param model $office_upload
     * @param model $office_upload_attachment
     * @return bool
     * @throws \yii\db\Exception
     * @author Mehran
     */
    public function updateLetter($office_main, $office_upload, $office_upload_attachment)
    {
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            if ($office_main->officeType->name == "ورودی") {
                $office_upload->photo = UploadedFile::getInstances($office_upload, 'photo');
                $office_upload_attachment->attachment = UploadedFile::getInstances($office_upload_attachment, 'attachment');
                /**
                 * Save attachment photo
                 */
                if ($office_upload_attachment->attachment != null) {
                    foreach ($office_upload_attachment->attachment as $image) {
                        $file_path_attachment = 'uploads/secretariat_attachment/' . date('Y-m-d-H-i-s') . "_" . $office_main->id . "_" . md5(uniqid() . rand(1111, 9999) . rand(11111, 99999)) . '.' . $image->extension;
                        $secretariat_attachment = new OfficeAttachment();
                        $secretariat_attachment->office_id = $office_main->id;
                        $secretariat_attachment->path = $file_path_attachment;
                        $secretariat_attachment->save();
                        $image->saveAs($file_path_attachment);
                    }
                }
                /**
                 * Save upload image
                 */
                if ($office_upload->photo != null) {
                    OfficePhoto::deleteAll(['office_id' => $office_main->id]);
                    foreach ($office_upload->photo as $photo) {
                        $file_path = 'uploads/input_secretariat_photos/' . date('Y-m-d-H-i-s') . "_" . $office_main->id . "_" . md5(uniqid() . rand(1111, 9999) . rand(11111, 99999)) . '.' . $photo->extension;
                        $secretariat_photo = new OfficePhoto();
                        $secretariat_photo->office_id = $office_main->id;
                        $secretariat_photo->path = $file_path;
                        $secretariat_photo->save();
                        $photo->saveAs($file_path);
                    }
                }
                /**
                 * Save secretariat details
                 */
                $office_main->save();
            } else {
                $transcript = Yii::$app->request->post()['OfficeMain']['transcript'];
                if ($transcript != null) {
                    OfficeTranscript::deleteAll(['office_id' => $office_main->id]);
                }
                $office_upload->photo = UploadedFile::getInstances($office_upload, 'photo');
                $office_upload_attachment->attachment = UploadedFile::getInstances($office_upload_attachment, 'attachment');
                if ($transcript != null) {
                    foreach ($transcript as $key => $value) {
                        $new_secretariat_transcript = new OfficeTranscript();
                        $new_secretariat_transcript->office_people_id = $value;
                        $new_secretariat_transcript->office_id = $office_main->id;
                        $new_secretariat_transcript->save();
                    }
                }
                /**
                 * Save attachment photo
                 */
                $office_upload_attachment->attachment = UploadedFile::getInstances($office_upload_attachment, 'attachment');
                if ($office_upload_attachment->attachment != null) {
                    foreach ($office_upload_attachment->attachment as $image) {
                        $file_path_attachment = 'uploads/secretariat_attachment/' . date('Y-m-d-H-i-s') . "_" . $office_main->id . "_" . md5(uniqid() . rand(1111, 9999) . rand(11111, 99999)) . '.' . $image->extension;
                        $secretariat_attachment = new OfficeAttachment();
                        $secretariat_attachment->office_id = $office_main->id;
                        $secretariat_attachment->path = $file_path_attachment;
                        $secretariat_attachment->save();
                        $image->saveAs($file_path_attachment);
                    }
                }
                /**
                 * Save upload image
                 */
                if ($office_upload->photo != null) {
                    OfficePhoto::deleteAll(['office_id' => $office_main->id]);
                    foreach ($office_upload->photo as $photo) {
                        $file_path = 'uploads/input_secretariat_photos/' . date('Y-m-d-H-i-s') . "_" . $office_main->id . "_" . md5(uniqid() . rand(1111, 9999) . rand(11111, 99999)) . '.' . $photo->extension;
                        $secretariat_photo = new OfficePhoto();
                        $secretariat_photo->office_id = $office_main->id;
                        $secretariat_photo->path = $file_path;
                        $secretariat_photo->save();
                        $photo->saveAs($file_path);
                    }
                }
                /**
                 * Save secretariat details
                 */
                $office_main->save();
            }

            $transaction->commit();

            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * @param $office_folder
     * @param $office_id
     * @return bool
     * @throws \yii\db\Exception
     * @author Mehran
     */
    public function UpdateLetterFolder($office_folder, $office_id)
    {
        $db = \Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            if ($office_folder->folder_type_id != null) {
                foreach ($office_folder->folder_type_id as $folder_id) {
                    /*$folder = SecretariatFolderType::findOne($folder_id);
                    $extraColumns = ['office_id' => $office_main->id];
                    $office_folder->link('folder', $folder, $extraColumns);*/

                    $office_t = new OfficeFolder();
                    $office_t->office_id = $office_id;
                    $office_t->folder_id = $folder_id;
                    $office_t->save();
                }
            }
            $transaction->commit();

            return true;
        } catch (Exception $e) {
            $transaction->rollback();

            return false;
        }
    }

    /**
     * @param integer $folder_id
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @author Mehran
     */
    public function DeleteLetterFolder($folder_id)
    {
        $office_folder = OfficeFolder::findOne($folder_id);

        if ($folder_id == null) {
            Throw new Exception("Folder Not found!");
        }

        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $office_folder->delete();
            $transaction->commit();

            return true;
        } catch (Exception $e) {
            $transaction->rollback();

            return false;
        }
    }

    /**
     * @param $office_main
     * @param $office_upload
     * @param $office_output
     * @return bool
     * @throws \yii\db\Exception
     * @author Mehran
     */
//    public function outputLetter($office_main, $office_upload, $office_output)
//    {
//        $office_id = $office_main->id;
//        $db = Yii::$app->db;
//        $transaction = $db->beginTransaction();
//
//        /**
//         * Save photo Or Save LetterContent
//         */
//        $office_upload->photo = UploadedFile::getInstances($office_upload, 'photo');
//        if ($office_upload->photo != null) {
//            try {
//                $office_output = $office_main->officeOutputs;
//                foreach ($office_upload->photo as $photo) {
//                    $file_path = 'uploads/output_secretariat_photos/' . date('Y-m-d-H-i-s') . "_" . $office_id . "_" . md5(uniqid() . rand(1111, 9999) . rand(11111, 99999)) . '.' . $photo->extension;
//                    $office_photo = new OfficePhoto();
//                    $office_photo->office_id = $office_id;
//                    $office_photo->path = $file_path;
//                    $office_photo->save();
//                    $photo->saveAs($file_path);
//                }
//                $office_main->status = "accept";
//                $office_main->save();
//
//                $office_output->is_signed = 1;
//                $office_output->save();
//
//                $transaction->commit();
//
//                return true;
//            } catch (Exception $e) {
//                $transaction->rollBack();
//
//                return false;
//            }
//
//        } elseif ($office_output->content != null) {
//            try {
//                $html = $this->letterContent($office_main->archive_number, $office_main->receiver_id, $office_output->content);
//                /**
//                 * Save Letter in secretariat Table
//                 */
//                $office_output->content = $html;
//                $office_output->save();
//
//                $office_main->status = "accept";
//                $office_main->save();
//
//                $transaction->commit();
//
//                return true;
//            } catch (Exception $e) {
//                $transaction->rollBack();
//
//                return false;
//            }
//
//        } else {
//            return false;
//        }
//    }

    /**
     * @param $archive_number
     * @param $receiver_id
     * @param $content
     * @return string
     * @author Mehran
     */
//    public function letterContent($archive_number, $receiver_id, $content)
//    {
//        /**
//         * In This Section Add Part For Get Content From CKEditor
//         */
//        $number = $archive_number;
//        $date = Intldate::get()->timestampToPersian(time(),'yyyy/MM/dd');
//        $receiver_user = SecretariatPeople::findOne($receiver_id);
//
//        $position = null;
//        $receiver = null;
//        $office = null;
//        if ($receiver_user != null) {
//            $receiver = $receiver_user->name . " " . $receiver_user->family;
//            $position = $receiver_user->occuaption_name;
//            $position .= " محترم ";
//            $office = $receiver_user->company_name;
//        }
//
//        $html =
//            '<table style="width:100%;direction: rtl">
//                <tr>
//                    <td style="width: 20%;">
//                        <img src="/asset_admin/custom/img/logoc.png" style="width: 70px; height: 70px; margin-right: 38px;" />
//                        <div style="margin-right: 20px;">بهار سامانه شرق</div>
//                    </td>
//                    <td style="width: 60%; text-align: center">
//                        <h4 style="font-weight: bold"> به نام خدا </h4>
//                    </td>
//                    <td style="width: 20%; padding-top: 50px">
//                        <div style="text-align: left"> تاریخ : ' . $date .' </div>
//                        <div style="text-align: left"> شماره نامه : ' . $number .' </div>
//                    </td>
//                </tr>
//            </table>
//            <div style="padding: 30px 20px 0px 20px; text-align: right; width: 100%">
//                <div style="font-weight: bold; font-size: 12pt; margin-bottom: 10px;"> ' . $receiver .'  </div>
//                <div style="font-weight: bold; font-size: 12pt;"> ' . $position . $office .'  </div>
//            </div>
//            <br/>
//            <div style="padding: 10px 20px; text-align: right">'. $content . ' </div>
//            <div style="padding: 10px 20px;">
//                <div style="font-weight: bold; font-size: 12pt; text-align: right;">باتشکر</div>
//            </div>
//            <footer style="width: 100%;margin-top:10px; text-align: left; direction: rtl;">
//               <div style="padding:10px; margin: 20px 0px">
//                    <table style="float: left; font-size: 12px;">
//                        <tr><td colspan="3"><span style="font-weight: bold">آدرس:</span> تهران، خیابان شیراز، کوچه ژاله، پلاک 6</td></tr>
//                        <tr>
//                            <td><span style="font-weight: bold">کد پستی:</span> 53381-14369</td>
//                            <td><span style="font-weight: bold;">تلفن:</span> 42576000-021</td>
//                            <td><span style="font-weight: bold;">پست الکترونیکی:</span> info@bahar.network</td>
//                        </tr>
//                        <tr><td colspan="3">شرکت بهار سامانه شرق دارای مجوز servco به شماره پروانه 1009548 از سازمان تنظیم مقررات رادیویی</td></tr>
//                    </table>
//               </div>
//            </footer>';
//
//        return $html;
//    }

    /**
     * @param $archive_number
     * @throws \yii\base\Exception
     * @author Mehran
     */
    public function duplicateLetterNumber($archive_number)
    {
        $check_letter = OfficeMain::findOne(['archive_number' => $archive_number]);
        if ($check_letter != null) {
            Throw new \yii\base\Exception("The Archive number is invalid!!!");
        }
    }

    /**
     * @param $office_number
     * @param $archive_number
     * @throws \yii\base\Exception
     * @author Mehran
     */
    public function duplicateLetterOfficeNumber($office_number, $archive_number)
    {
        $check_letter = OfficeInput::findOne(['number' => $office_number]);
        if ($office_number != null) {
            if ($check_letter != null && $office_number != null) {
                Throw new Exception('The Letter number is invalid');
            }

            if ((int)explode("-", $archive_number >= 4054)) {
                Throw new \yii\base\Exception("The Archive number for old letters is invalid!!!");
            }
        }
    }

    /**
     * @param $category_id
     * @param $secretariat_numbers
     * @throws Exception
     * @author Mehran
     */
    public function checkLetterCategory($category_id, $secretariat_numbers)
    {
        if ($category_id == 3 || $category_id == 2 || $category_id == 4) {
            if (empty($secretariat_numbers)) {
                Throw new Exception("The category number is Requird!!");
            }
        }
    }

    /**
     * @param $id
     * @param $status
     * @param $view
     * @return mixed
     * @throws \yii\base\Exception
     * @author Noie
     */
    public function deleteLetter($id, $status, $view)
    {
        if (!Yii::$app->user->can('SecretariatDeleteLetterPerm')) {
            MessageHandler::ShowMessage('خطای دسترسی', 'error');
            return $this->redirect('list-letters');
        }

        $secretariat = OfficeMain::findOne($id);

        switch ($status) {
            case 'delete':
                if ($secretariat != null) {
                    // delete letters
                    if ($secretariat->is_delete == 1){
                        MessageHandler::ShowMessage('خطا در ورودی پارامترها', 'error');
                        if ($view == 'private') {
                            return $this->redirect('list-private-letters');
                        } else {
                            return $this->redirect('list-letters');
                        }
                    }else {
                        $secretariat->is_delete = 1;
                        $secretariat->save();

                        MessageHandler::ShowMessage('باموفقیت حذف شد', 'success');
                        if ($view == 'private') {
                            return $this->redirect('list-private-letters');
                        } else {
                            return $this->redirect('list-letters');
                        }
                    }

                } else {
                    MessageHandler::ShowMessage('خطا در ورودی پارامترها', 'error');
                    if ($view == 'private') {
                        return $this->redirect('list-private-letters');
                    } else {
                        return $this->redirect('list-letters');
                    }
                }

            default:
                MessageHandler::ShowMessage('خطا در ورودی پارامترها', 'error');
                if ($view == 'private') {
                    return $this->redirect('list-private-letters');
                } else {
                    return $this->redirect('list-letters');
                }
        }
    }

    /**
     * @param $office_id
     * @return bool
     * @throws Exception
     * @author Mehran
     */
    public function verifyLetter($office_id)
    {
        if ($office_id == null) {
            Throw new Exception("The Letter id is Required");
        }

        $office_main = OfficeMain::findOne($office_id);
        if ($office_main == null) {
            Throw new Exception("Letter not found!");
        }
        return true;
    }

    /**
     * @param $office_main
     * @return bool
     * @throws Exception
     * @author Mehran
     */
    /*public function confirmationLetter($office_main)
    {
        $user_id = Yii::$app->user->identity->id;
        $user = User::findOne($user_id);

        if ($user == null) {
            Throw new Exception("User Not found");
        }

        $surname = $user->profile->name . "  " . $user->profile->family;

        $user_signature = UserSignature::find()
            ->where(['user_id' => $user_id])
            ->one();

        $signarue_image = null;
        if ($user_signature != null) {
            $signarue_image =  '<img src="/' . $user_signature->path . ' " style="width:200px; heigth: 100px;" alt="Eror404"/>';
        }


        $office_output = $office_main->officeOutputs;

        $content = str_replace('<div style="font-weight: bold; font-size: 12pt; text-align: right;">باتشکر</div>',
            '<div style="font-weight: bold; font-size: 12pt; text-align: center;">
                        <p style="font-weight: bold; font-size: 12pt;"> ' . $signarue_image . ' </p>
                        <p style="font-weight: bold; font-size: 12pt;">باتشکر</p>
                        <p style="font-weight: bold; font-size: 12pt;">' . $surname . '</p>
                     </div>',
            $office_output->content);
        $office_output->content = $content;
        $office_output->is_signed = 1;
        $office_output->save();

        return true;
    }*/

    /**
     * @param $office_id
     * @return bool
     * @throws Exception
     * @author Mehran
     */
    /*public function archiveLetter($office_id)
    {
        $office_main = OfficeMain::findOne($office_id);
        if ($office_main == null) {
            Throw new Exception("Letter not found!");
        }

        $office_main->archive_flag = 1;
        if (!$office_main->save()) {
            Throw new Exception("U can not save letter!");
        }
        return true;
    }*/

    /**
     * @param $office_main
     * @return mixed
     * @author Mehran
     */
    /*public function downloadLetter($office_main)
    {
        $office_output = $office_main->officeOutputs;
        $content = $office_output->content;

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => ['title' => 'Krajee Report Title'],

        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }*/

    /**
     * @param $office_main
     * @param $office_output
     * @param $letter_info
     * @return bool
     * @throws \yii\db\Exception
     * @author mehran
     */
    /*public function renderLastLetter($office_main, $office_output, $letter_info)
    {
        $generator = new CodeGenerator();

        $db = \Yii::$app->db;
        $transaction = $db->beginTransaction();

        try {
            $office_main->assigned_person = 842;
            if ($office_output->content == null)
            {
                Throw new Exception("The content of letter is Required!");
            }

            $actionator_id = null;

            $new_office = new OfficeMain();
            $new_office->archive_prefix = null;
            $new_office->archive_number = $generator->generateCode('output_letter');;
            $new_office->date = time();
            $new_office->title = $office_main->title;
            $new_office->description = $office_main->description;
            $new_office->status = 'accept';
            $new_office->archive_flag = 0;
            $new_office->date = time();
            $new_office->sender_id = $letter_info['sender_id'];
            //office_type_id == 2 ===> Output Letter
            $new_office->office_type_id = 2;
            $new_office->access_level_id =  $office_main->access_level_id;
            $new_office->priority_id =  $office_main->priority_id;
            $new_office->category_id =  $office_main->category_id;
            $new_office->letter_date =  $office_main->letter_date;
            $new_office->save();

            $content = $this->renderLastLetterContent($office_output->content, $new_office->date, $new_office->archive_number, $letter_info['receiver'], $letter_info['occupation'], $letter_info['title'], $letter_info['sender']);

            $office_output->office_id = $new_office->id;
            $office_output->is_old = 0;
            $office_output->is_signed = 0;
            $office_output->content = $content;
            $office_output->actionator_id = null;
            $office_output->receiver_id = $office_main->sender_id;
            $office_output->save();

            $office_assign = new OfficeAssign();
            $office_assign->link('user', User::findOne($office_main->assigned_person));
            $office_assign->link('office', $office_main);
            $office_assign->depth = 0;
            $office_assign->save();

            $transaction->commit();
            return true;

        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }*/

    /**
     * @param $content
     * @param $date
     * @param $number
     * @param $receiver
     * @param $occupation
     * @param $title
     * @param $sender
     * @return string
     * @author Mehran
     */
    /*public function renderLastLetterContent($content, $date, $number, $receiver, $occupation, $title, $sender)
    {
        $html = '<table style="width:100%;direction: rtl">
                        <tr>
                            <td style="width: 20%;">                                
                                <img src="asset_admin/custom/img/logoc.png" style="width: 70px; height: 70px; margin-right: 10px;" />
                                <p style="margin-right: 20px; margin-top: 20px;"> بهار سامانه شرق</p>
                            </td>
                            <td style="width: 55%; text-align: center">
                                <h4 style="font-weight: bold; font-family:Tahoma "> به نام خدا </h4>
                            </td>
                            <td style="width: 25%; padding-top: 50px">
                                <p style="text-align: left; font-family: Tahoma">  تاربخ : ' . $date . '  </p>
                                <p style="text-align: left">  شماره نامه : ' . $number . '  </p>
                            </td>
                        </tr>
                    </table>
        
                    <div style="width: 100%; margin-right: 20px;" >
                        <div style="padding: 30px 20px 0px 20px; text-align: right; width: 100%">
                            <p style="font-weight: bold; font-size: 12pt; margin-bottom: 10px;"> دریافت کننده :   جناب ' . $receiver  .'</p>
                            <p style="font-weight: bold; font-size: 12pt;">   ' . $occupation .  '</p>
                            <br/>
                            <br/>
                            <p style="font-weight: bold; font-size: 14pt; margin-bottom: 10px; text-align: right"> عنوان :  ' . $title  .'</p>
       
                        </div>
                    </div>
                    <div style="width: 100%">
                        <div style="padding: 10px 50px 0px 0px; text-align: right; width: 100%">
                            <p style=" font-size: 10pt;"> با سلام و احترام </p>
        
                        </div>
                    </div>';
        $html .= '<div style="padding: 10px 50px 0px 0px; text-align: right;">' . $content . '</div>';
        $html .= '<div style="width: 100%; text-align: left; padding-left: 100px; padding-top: 10px">
                            <div id="signeture"></div>
                            <h4 style="font-weight: bold; font-size: 12pt; margin-top: -20px; padding-left: 90px">با تشکر </h4>
                            <h4 style="font-weight: bold; font-size: 12pt; padding-left: 50px">  ' . $sender . ' </h4>
                       </div>
                       <hr/>
                       <div class="row">
                           <div class="col-sm-12 text-left" style="direction: rtl;">
                               <div style="padding:10px; margin: 20px 0px">
                                   <table style="float: left; font-size: 12px;">
                                       <tr><td colspan="3"><span style="font-weight: bold">آدرس:</span> تهران، خیابان شیراز، کوچه ژاله، پلاک 6</td></tr>
                                       <tr>
                                           <td><span style="font-weight: bold">کد پستی:</span> 53381-14369</td>
                                           <td><span style="font-weight: bold;">تلفن:</span> 42576000-021</td>
                                           <td><span style="font-weight: bold;">پست الکترونیکی:</span> info@bahar.network</td>
                                       </tr>
                                       <tr><td colspan="3">شرکت بهار سامانه شرق دارای مجوز servco به شماره پروانه 1009548 از سازمان تنظیم مقررات رادیویی</td></tr>
                                   </table>
                               </div>
                           </div>
                       </div>';

        return $html;
    }*/

    public function findReceiver($id = null)
    {
        if ($id != null) {
            $reciver_data = SecretariatPeople::find()->joinWith(['secretariats'])->where(['secretariat.id' => $id])->andWhere(['secretariat_people.reseller_id' => Yii::$app->user->identity->reseller_id])->all();
        } else {
            $reciver_data = SecretariatPeople::find()->where(['reseller_id' => Yii::$app->user->identity->reseller_id])->all();
        }
        if ($reciver_data != null) {
            foreach ($reciver_data as $key => $value) {
                $data[$value->id]['id'] = $value->id;
                $data[$value->id]['name'] = $value->name . ' ' . $value->family . ' ' . $value->occuaption_name . ' ' . $value->unit_name . ' ' . $value->company_name . ' - ' . $value->tel;
            }
            $result = ArrayHelper::map($data, 'id', 'name');
        } else {
            $result = [];
        }
        return $result;
    }

    public function findPeople($people_ids)
    {
        $reciver_data = OfficePeople::find()->Where(['in', 'id', $people_ids])->all();
        if ($reciver_data != null) {
            foreach ($reciver_data as $key => $value) {
                $data[$value->id]['id'] = $value->id;
                $data[$value->id]['name'] = $value->name . ' ' . $value->family . ' ' . $value->occuaption_name . ' ' . $value->unit_name . ' ' . $value->company_name . ' - ' . $value->tel;
            }
            $result = ArrayHelper::map($data, 'id', 'name');
        } else {
            $result = [];
        }
        return $result;
    }

    public function findSecretariatNumbers()
    {
        $secretariat_numbers = OfficeMain::find()
            ->Where(['reseller_id' => Yii::$app->user->identity->reseller_id])
            ->andWhere(['status_id' => 1])
            ->All();
        if ($secretariat_numbers != null) {

            foreach ($secretariat_numbers as $row => $item) {
                $data[$item->id]['id'] = $item->id;
                $data[$item->id]['name'] = $item->archive_prefix . $item->archive_number;
            }

            return ArrayHelper::map($data, 'id', 'name');
        }
    }

//    public function getSecretariatEmployees()
//    {
//        return ArrayHelper::map(User::find()
//            ->joinWith(['flowOccupation'])
//            ->where(['reseller_id' => 1])
//            ->andWhere(['user_type_id' => 1])
//            ->all(), 'id', function($employee) {
//            $occupation = null;
//            if (isset($employee->flowOccupation->occupationData)) {
//                $occupation = " - " .  $employee->flowOccupation->occupationData->name . '(' . $employee->flowOccupation->occupationData->description . ')';
//            }
//
//            if ($employee->profile != null){
//                $employee->profile->name = $employee->profile->name . " " . $employee->profile->family . $occupation ;
//            }
//
//            return $employee->profile->name;
//        } );
//    }

    public function getEmployees()
    {
        $employees = User::find()
            ->where(['reseller_id' => 1])
            ->andWhere(['user_type_id' => 1])
            ->andWhere(['not', ['humanresource_id' => null]])
            ->all();
        foreach ($employees as $employee) {
            $employee->profile->name = $employee->profile->name . " " . $employee->profile->family;
        }
        $employees = ArrayHelper::map($employees,'humanresource_id','profile.name');
        return $employees;
    }

    public function getEmployeeUsers()
    {
        $employees = User::find()
            ->where(['reseller_id' => 1])
            ->andWhere(['user_type_id' => 1])
            ->andWhere(['not', ['humanresource_id' => null]])
            ->all();
        foreach ($employees as $employee) {
            $employee->profile->name = $employee->profile->name . " " . $employee->profile->family;
        }
        $employees = ArrayHelper::map($employees,'id','profile.name');
        return $employees;
    }
}