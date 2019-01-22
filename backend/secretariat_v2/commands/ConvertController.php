<?php
namespace app\modules\secretariat_v2\commands;

use app\modules\secretariat\models\EceSecretariat;
use app\modules\secretariat\models\SecretariatAssign;
use app\modules\secretariat\models\SecretariatAttachment;
use app\modules\secretariat\models\SecretariatFolder;
use app\modules\secretariat\models\SecretariatPeople;
use app\modules\secretariat\models\SecretariatPhoto;
use app\modules\secretariat\models\SecretariatRelation;
use app\modules\secretariat\models\SecretariatTranscript;
use app\modules\secretariat_v2\models\OfficeAssign;
use app\modules\secretariat_v2\models\OfficeAttachment;
use app\modules\secretariat_v2\models\OfficeDeadline;
use app\modules\secretariat_v2\models\OfficeEce;
use app\modules\secretariat_v2\models\OfficeFolder;
use app\modules\secretariat_v2\models\OfficeMain;
use app\modules\secretariat\models\Secretariat;
use app\modules\secretariat\models\SecretariatFolderType;
use app\modules\secretariat_v2\models\OfficeAccessLevel;
use app\modules\secretariat_v2\models\OfficeCategory;
use app\modules\secretariat_v2\models\OfficeFolderType;
use app\modules\secretariat_v2\models\OfficeInput;
use app\modules\secretariat_v2\models\OfficeOutput;
use app\modules\secretariat_v2\models\OfficePeople;
use app\modules\secretariat_v2\models\OfficePhoto;
use app\modules\secretariat_v2\models\OfficePriority;
use app\modules\secretariat_v2\models\OfficeRelation;
use app\modules\secretariat_v2\models\OfficeStatus;
use app\modules\secretariat_v2\models\OfficeTranscript;
use app\modules\secretariat_v2\models\OfficeType;
use Exception;
use Yii;
use yii\console\Controller;

class ConvertController extends Controller
{
    public function actionTest()
    {
        $secretariat = Secretariat::find()->all();

        foreach ($secretariat as $key => $value) {
            $office_main = new OfficeMain();

            /**
             * Find the LetterTypeId
             */
            $office_type_id = null;
            if ($value['type'] != null) {
                $office_type = OfficeType::findOne(['name' => $value['type']]);
                $office_type_id = $office_type->id;
            }
            /**
             * Find the OfficeAccessLevelId
             */
            $access_level_id = null;
            if ($value['access_level'] != null) {
                $access_level = OfficeAccessLevel::findOne(['name' => $value['access_level']]);
                if ($access_level != null) {
                    $access_level_id = $access_level->id;
                }
            }
            /**
             * Find the OfficeCategoryId
             */
            $category_id  = null;
            if ($value['category'] != null) {
                $category = OfficeCategory::findOne(['name' => $value['category']]);
                $category_id = $category->id;
            }

            /**
             * Find the OfficePriorityId
             */
            $priority_id  = null;
            if ($value['priority'] != null) {
                $priority = OfficePriority::findOne(['name' => $value['priority']]);
                $priority_id = $priority->id;
            }

            $office_main->id = $value['id'];
            $office_main->archive_prefix = null;
            $office_main->archive_number = $value['archive_number'];
            $office_main->date = $value['date'];
            $office_main->title = $value['title'];
            $office_main->description = $value['description'];
            $office_main->status_id = 1;
            $office_main->is_archived = $value['archive_flag'];
            $office_main->sender_id = $value['sender_id'];
            $office_main->office_type_id = $office_type_id;
            $office_main->access_level_id = $access_level_id;
            $office_main->priority_id = $priority_id;
            $office_main->category_id = $category_id;
            $office_main->reseller_id = $value['reseller_id'];
            $office_main->created_at = $value['created_at'];
            $office_main->created_by = $value['creator_id'];
            $office_main->updated_at = $value['created_at'];
            $office_main->updated_by = $value['creator_id'];
            $office_main->isDeleted = 0;
            $office_main->letter_date = null;
            $office_main->save();
            if ($office_main->errors != null) {
                pd($office_main->errors);
            }
            print_r($office_main->errors);die();
            echo "The letter with id " . $office_main->id . "has been added successfully" . "<br/>";

            print_r("Mehran");die();
        }

        echo "<pre>";
        print_r("its Done!!!!");
        die();
    }

    /**
     * This action for Covert SecretariatfolderType to OfficeFolderType
     * We should run this action first for convert
     * @author Mehran
     */
    public function actionOfficeFolderType()
    {
        $folder_typies = SecretariatFolderType::find()->all();

        foreach ($folder_typies as $key => $value) {
            $office_folder_type = new OfficeFolderType();
            $office_folder_type->id = $value['id'];
            $office_folder_type->name = $value['name'];
            $office_folder_type->reseller_id = $value['reseller_id'];

            $office_folder_type->created_at = time();
            $office_folder_type->created_by = 2258;
            $office_folder_type->updated_at = time();
            $office_folder_type->updated_by = 2258;
            $office_folder_type->save();
            echo "The folder " . $value['name'] . " has been added successfully" . "<br/>";
        }

        echo "<pre>";
        print_r("its Done!!!!");
        die();
    }

    /**
     * This action for Covert Secretariat to OfficeMain, officeOutput and OfficeInput
     * We should run this action,the second one for convert
     * @author Mehran
     */
    public function actionSecretariatLetter()
    {
        $secretariat = Secretariat::find()->all();

        foreach ($secretariat as $key => $value) {
            $office_main = new OfficeMain();

            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                /**
                 * Find the LetterTypeId
                 */
                $office_type_id = null;
                if ($value['type'] != null) {
                    $office_type = OfficeType::findOne(['name' => $value['type']]);
                    $office_type_id = $office_type->id;
                }
                /**
                 * Find the OfficeAccessLevelId
                 */
                $access_level_id = null;
                if ($value['access_level'] != null) {
                    $access_level = OfficeAccessLevel::findOne(['name' => $value['access_level']]);
                    if ($access_level != null) {
                        $access_level_id = $access_level->id;
                    }
                }
                /**
                 * Find the OfficeCategoryId
                 */
                $category_id  = null;
                if ($value['category'] != null) {
                    $category = OfficeCategory::findOne(['name' => $value['category']]);
                    $category_id = $category->id;
                }

                /**
                 * Find the OfficePriorityId
                 */
                $priority_id  = null;
                if ($value['priority'] != null) {
                    $priority = OfficePriority::findOne(['name' => $value['priority']]);
                    $priority_id = $priority->id;
                }/**
                 * Find the OfficePriorityId
                 */
                $status_id  = null;
                if ($value['priority'] != null) {
                    $status = OfficeStatus::findOne(['name' => $value['status']]);
                    $status_id = $status->id;
                }

                $office_main->id = $value['id'];
                $office_main->archive_prefix = null;
                $office_main->archive_number = $value['archive_number'];
                $office_main->date = $value['date'];
                $office_main->title = $value['title'];
                $office_main->description = $value['description'];
                $office_main->status_id = $status_id;
                $office_main->is_archived = $value['archive_flag'];
                $office_main->sender_id = $value['sender_id'];
                $office_main->office_type_id = $office_type_id;
                $office_main->access_level_id = $access_level_id;
                $office_main->priority_id = $priority_id;
                $office_main->category_id = $category_id;
                $office_main->reseller_id = 1;
                $office_main->reseller_id = $value['reseller_id'];
                $office_main->created_at = $value['created_at'];
                $office_main->created_by = $value['creator_id'];
                $office_main->updated_at = $value['created_at'];
                $office_main->updated_by = $value['creator_id'];
                $office_main->isDeleted = 0;
                $office_main->letter_date = null;
                $office_main->save();
                if ($office_main->errors != null) {
                    echo "<pre>";
                    print_r($office_main->errors);
                    die();
                }

                if ($value['type'] == 'خروجی') {
                    $office_output = new OfficeOutput();

                    $office_output->office_id = $office_main->id;
                    $office_output->is_old = (string)$value['is_old'];
                    $office_output->is_signed = (string)$value['is_signed'];
                    $office_output->content = $value['content'];
                    $office_output->actionator_id = $value['actionator_id'];
                    $office_output->receiver_id = $value['receiver_id'];
                    $office_output->save();

                    if ($office_output->errors != null) {
                        echo "<pre>";
                        print_r($office_output->errors);
                        die();
                    }
                    echo "The letter with id " . $office_main->id . "has been added successfully" . "<br/>";
                } else if ($value['type'] == 'ورودی') {
                    $office_input = new OfficeInput();

                    $office_input->office_id = $office_main->id;
                    $office_input->number = $value['secretariat_number'];
                    $office_input->save();
                    if ($office_input->errors != null) {
                        echo "<pre>";
                        print_r($office_input->errors);
                        die();
                    }

                    if ($value['deadline_date'] != null) {
                        $office_deadline = new OfficeDeadline();
                        $office_deadline->office_main_input_id = $office_input->id;
                        $office_deadline->dead_line = $value['deadline_date'];
                        $office_deadline->save();
                    }

                    echo "The letter with id " . $office_main->id . "has been added successfully" . "<br/>";
                }

                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();

                /*echo "\n";
                echo "U can not insert it!!!";
                echo "\n";*/
                print_r($e->getMessage());
                die();
            }
        }

        echo "<pre>";
        print_r("its Done!!!!");
        die();
    }

    /**
     * This action for Covert SecretariatAttachment to OfficeAttachment
     * We should run this action,the Third one for convert
     * @author Mehran
     */
    public function actionSecretariatLetterAttachment()
    {
        $secretariat_attachment = SecretariatAttachment::find()->all();

        foreach ($secretariat_attachment as $key => $value) {
            $office_attachment = new OfficeAttachment();

            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $office_attachment->id = $value['id'];
                $office_attachment->office_id = $value['secretariat_id'];
                $office_attachment->path = $value['path'];
                $office_attachment->created_at = $value['created_at'];
                $office_attachment->updated_at = $value['created_at'];
                $office_attachment->created_by = 2258;
                $office_attachment->updated_by = 2258;
                $office_attachment->save();

                echo "the letter attachment for letter_id" . $value['id'] . 'has been added successfuly' . "<br/>";
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                echo "\n";
                print_r($e->getMessage());
                echo "U can not insert it!!!";
                echo "\n";
            }
        }
        echo "<pre>";
        print_r("its Done!!!!");
        die();
    }

    /**
     * This action for Covert SecretariatPhoto to OfficePhoto
     * We should run this action,the Fourth one for convert
     * @author Mehran
     */
    public function actionSecretariatLetterPhoto()
    {
        $secretariat_photo = SecretariatPhoto::find()->all();

        foreach ($secretariat_photo as $key => $value) {
            $office_photo = new OfficePhoto();

            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $office_photo->id = $value['id'];
                $office_photo->office_id = $value['secretariat_id'];
                $office_photo->path = $value['path'];
                $office_photo->created_at = $value['created_at'];
                $office_photo->updated_at = $value['created_at'];
                $office_photo->created_by = 2258;
                $office_photo->updated_by = 2258;
                $office_photo->save();

                echo "the letter photo for letter_id" . $value['id'] . 'has been added successfuly' . "<br/>";
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                echo "\n";
                print_r($e->getMessage());
                echo "U can not insert it!!!";
                echo "\n";
            }
        }
        echo "<pre>";
        print_r("its Done!!!!");
        die();
    }

    /**
     * This action for Covert SecretariatPhoto to OfficePhoto
     * We should run this action,the Fourth one for convert
     * @author Mehran
     */
    public function actionSecretariatLetterEce()
    {
        $ece_secretariat = EceSecretariat::find()->all();

        foreach ($ece_secretariat as $key => $value) {
            $office_ece = new OfficeEce();

            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $secretariat = Secretariat::findOne(['ece_detail_id' => $value['id']]);
                $secretariat_id = $secretariat->id;

                $office_ece->id = $value['id'];
                $office_ece->office_id = $secretariat_id;
                $office_ece->sender_organization = $value['sender_organization'];
                $office_ece->sender_department = $value['sender_department'];
                $office_ece->sender_position = $value['sender_position'];
                $office_ece->sender_name = $value['sender_name'];
                $office_ece->sender_code = $value['sender_Code'];
                $office_ece->receiver_organization = $value['receiver_organization'];
                $office_ece->receiver_department = $value['receiver_department'];
                $office_ece->receiver_position = $value['receiver_position'];
                $office_ece->receiver_name = $value['receiver_name'];
                $office_ece->receiver_code = $value['receiver_Code'];
                $office_ece->path = $value['path'];
                $office_ece->save();

                echo "the information for ece letter for letterId: " . $value['id'] . 'has been added successfuly' . "<br/>";
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                echo "\n";
                print_r($e->getMessage());
                echo "U can not insert it!!!";
                echo "\n";
            }
        }
        echo "<pre>";
        print_r("its Done!!!!");
        die();
    }

    /**
     * This action for Covert SecretariatPeople to OfficePeople
     * We should run this action,the fifth one for convert
     * @author Mehran
     */
    public function actionSecretariatLetterPeople()
    {
        $secretariat_people = SecretariatPeople::find()->all();

        foreach ($secretariat_people as $key => $value) {
            $office_people = new OfficePeople();

            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $office_people->id = $value['id'];
                $office_people->name = $value['name'];
                $office_people->company_name = $value['company_name'];
                $office_people->occuaption_name = $value['occuaption_name'];
                $office_people->unit_name = $value['unit_name'];
                $office_people->family = $value['family'];
                $office_people->reseller_id = $value['reseller_id'];
                $office_people->tel = $value['tel'];
                $office_people->created_at = time();
                $office_people->updated_at = time();
                $office_people->created_by = 2258;
                $office_people->updated_by = 2258;
                $office_people->save();

                echo "the information for ece letter for letterId: " . $value['id'] . 'has been added successfuly' . "<br/>";
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                echo "\n";
                print_r($e->getMessage());
                echo "U can not insert it!!!";
                echo "\n";
            }
        }
        echo "<pre>";
        print_r("its Done!!!!");
        die();
    }

    /**
     * This action for Covert SecretariatTranscript to OfficeTranscript
     * We should run this action,the sixth one for convert
     * @author Mehran
     */
    public function actionSecretariatLetterTranscript()
    {
        $secretariat_transcript = SecretariatTranscript::find()->all();

        foreach ($secretariat_transcript as $key => $value) {
            $office_transcript = new OfficeTranscript();

            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $office_transcript->id = $value['id'];
                $office_transcript->office_id = $value['secretariat_id'];
                $office_transcript->office_people_id = $value['secretariat_people_id'];
                $office_transcript->created_at = time();
                $office_transcript->updated_at = time();
                $office_transcript->created_by = 2258;
                $office_transcript->updated_by = 2258;
                $office_transcript->save();

                echo "the information for ece letter for letterId: " . $value['id'] . 'has been added successfuly' . "<br/>";
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                echo "\n";
                print_r($e->getMessage());
                echo "U can not insert it!!!";
                echo "\n";
            }
        }
        echo "<pre>";
        print_r("its Done!!!!");
        die();
    }

    /**
     * This action for Covert SecretariatRelation to OfficeRelation
     * We should run this action,the seventh one for convert
     * @author Mehran
     */
    public function actionSecretariatLetterRelation()
    {
        $secretariat_relation = SecretariatRelation::find()->all();

        foreach ($secretariat_relation as $key => $value) {
            $office_relation = new OfficeRelation();

            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $office_relation->id = $value['id'];
                $office_relation->office_id = $value['secretariat_id'];
                $office_relation->office_relation_id = $value['secretariat_relation_id'];
                $office_relation->office_reltype_id = $value['secretariat_reltype_id'];
                $office_relation->family_id = $value['family_id'];
                $office_relation->created_at = time();
                $office_relation->updated_at = time();
                $office_relation->created_by = 2258;
                $office_relation->updated_by = 2258;
                $office_relation->save();

                echo "the information for ece letter for letterId: " . $value['id'] . 'has been added successfuly' . "<br/>";
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                echo "\n";
                print_r($e->getMessage());
                echo "U can not insert it!!!";
                echo "\n";
            }
        }
        echo "<pre>";
        print_r("its Done!!!!");
        die();
    }

    /**
     * This action for Covert SecretariatRelation to OfficeRelation
     * We should run this action,the eighth one for convert
     * @author Mehran
     */
    public function actionSecretariatFolder()
    {
        $secretariat_folder = SecretariatFolder::find()->all();

        foreach ($secretariat_folder as $key => $value) {
            $office_folder = new OfficeFolder();

            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $office_folder->id = $value['id'];
                $office_folder->office_id = $value['secretariat_id'];
                $office_folder->folder_id = $value['folder_id'];
                $office_folder->reseller_id = 1;
                $office_folder->created_at = time();
                $office_folder->updated_at = time();
                $office_folder->created_by = 2258;
                $office_folder->updated_by = 2258;
                $office_folder->save();

                echo "the information for folder letter for folderId: " . $value['id'] . 'has been added successfuly' . "<br/>";
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                echo "\n";
                print_r($e->getMessage());
                echo "U can not insert it!!!";
                echo "\n";
            }
        }
        echo "<pre>";
        print_r("its Done!!!!");
        die();
    }

    /**
     * This action for Covert SecretariatAssign to OfficeRAssign
     * We should run this action,the ninth one for convert
     * @author Mehran
     */
    public function actionSecretariatAssign()
    {
        $secretariat_assign = SecretariatAssign::find()->all();

        foreach ($secretariat_assign as $key => $value) {
            $office_assign = new OfficeAssign();

            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $office_assign->id = $value['id'];
                $office_assign->office_id = $value['secretariat_id'];
                $office_assign->user_id = $value['user_id'];
                $office_assign->parent_id = $value['parent_id'];
                $office_assign->paraph = $value['paraph'];
                $office_assign->is_seen = $value['is_seen'];
                $office_assign->depth = 0;
                $office_assign->created_at = $value['created_at'];
                $office_assign->updated_at = $value['created_at'];
                $office_assign->created_by = $value['user_id'];
                $office_assign->updated_by = $value['user_id'];
                $office_assign->save();

                echo "the information for folder letter for assign: " . $value['id'] . 'has been added successfuly' . "<br/>";
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                echo "\n";
                print_r($e->getMessage());
                echo "U can not insert it!!!";
                echo "\n";
            }
        }
        echo "<pre>";
        print_r("its Done!!!!");
        die();
    }

    /**
     * This action for set depth of assign tree relation
     * We should run this action,the tenth one for convert
     * @throws \yii\db\Exception
     * @author Mehran
     */
    public function actionAssignmentLetter()
    {
        $office_assign = OfficeAssign::find()->all();

        foreach ($office_assign as $key => $value) {
            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $depth = null;
                $office_assign_depth = OfficeAssign::findOne($value['id']);

                if ($value->parent_id == null) {
                    $depth = 0;
                } else {
                    $depth = $value->parentNode->depth + 1;
                }
                $office_assign_depth->depth = $depth;
                $office_assign_depth->save();

                echo "the depth of letter asign for letter: " . $value['office_id'] . 'has been updated successfuly' . "<br/>";
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                echo "\n";
                print_r($e->getMessage());
                echo "U can not insert it!!!";
                echo "\n";
            }
        }
        echo "<pre>";
        print_r("its Done!!!!");
        die();
    }
}
