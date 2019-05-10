<?php

namespace api\models;

use Yii;
use yii\base\Model;
use yii\db\Expression;
use api\modules\v1\models\UserFriend;
use api\modules\v1\models\CourseActivity;

/**
 * CourseActivityStatistic represents the model behind the search form about `api\modules\v1\models\CourseActivity`.
 */
class CourseActivityStatistic extends CourseActivity
{

    const TYPE_WEEK = 0;
    const TYPE_MONTH = 1;
    const TYPE_YEAR = 2;

    public $user_id;
    public $total_activity;
    public $total_distance;
    public $total_duration;
    public $total_calorie;
    public $average_speed;
    public $activity_date;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getCurrentStatsByDateDay()
    {
        $User = Yii::$app->user->identity;
        $where = [
            'user_id' => $User->getId()
        ];
        $andWhere = [
            'between',
            'activity_date',
            new Expression('CURDATE() - INTERVAL 4 WEEK'),
            new Expression('CURDATE()'),
        ];
        $UserStat = CourseActivity::find()
            ->select([
                'activity_date',
                'YEARWEEK(activity_date, 5) as date_year_week',
                'DAYNAME(activity_date) as date_week_day_name',
                'WEEKDAY(activity_date) as date_week_day_number',
                'sum(total_distance) as total_distance',
                'sum(total_hill) as total_hill',
                'sum(total_duration) as total_duration',
                //'TRUNCATE(sum(calorie)/1000, 2) as total_calorie',
                'sum(calorie) as total_calorie',
                'sum(average_speed) as average_speed',
                'sum(gain_of_life) as gain_of_life',
            ])
            ->where($where)
            ->andWhere($andWhere)
            ->groupBy(['activity_date'])
            ->asArray()
            ->all();
        return $UserStat;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getCurrentStatsByDateMonth()
    {
        $User = Yii::$app->user->identity;
        $where = [
            'user_id' => $User->getId()
        ];
        $andWhere = [
            'between',
            'activity_date',
            new Expression('CURDATE() - INTERVAL 12 MONTH'),
            new Expression('CURDATE()'),
        ];
        $UserStat = CourseActivity::find()
            ->select([
                'YEAR(activity_date) AS date_year',
                'MONTH(activity_date) AS date_month',
                'sum(total_distance) as total_distance',
                'sum(total_hill) as total_hill',
                'sum(total_duration) as total_duration',
                //'TRUNCATE(sum(calorie)/1000, 2) as total_calorie',
                'sum(calorie) as total_calorie',
                'sum(average_speed) as average_speed',
                'sum(gain_of_life) as gain_of_life',
            ])
            ->where($where)
            ->andWhere($andWhere)
            ->groupBy(['YEAR(activity_date), MONTH(activity_date)'])
            ->asArray()
            ->all();
        return $UserStat;
    }

    /**
     * @param int $type
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getFriendStatsByDate($type = 0)
    {
        switch($type)
        {
            case self::TYPE_WEEK:
            default:
                $andWhere = [
                    'YEAR(activity_date)'=> new Expression('YEAR(CURDATE())'),
                    'MONTH(activity_date)'=> new Expression('MONTH(CURDATE())'),
                    'WEEK(activity_date)'=> new Expression('WEEK(CURDATE())')
                ];
                break;
            case self::TYPE_MONTH:
                $andWhere = [
                    'YEAR(activity_date)'=> new Expression('YEAR(CURDATE())'),
                    'MONTH(activity_date)'=> new Expression('MONTH(CURDATE())')
                ];
                break;
            case self::TYPE_YEAR:
                $andWhere = [
                    'YEAR(activity_date)'=> new Expression('YEAR(CURDATE())')
                ];
                break;
        }

        $friendIds = UserFriend::getFriendIds();
        $User = Yii::$app->user->identity;
        $ids = array_merge([$User->getId()], $friendIds);
        $Stat = CourseActivity::find()
            ->select([
                'user_id',
                'sum(total_distance) as total_distance',
                'sum(total_hill) as total_hill',
                'sum(total_duration) as total_duration',
                //'TRUNCATE(sum(calorie)/1000, 2) as total_calorie',
                'sum(calorie) as total_calorie',
                'sum(calorie) as total_calorie_new',
                'sum(average_speed) as average_speed',
            ])
            ->where(['user_id' => $ids])
            ->andWhere($andWhere)
            ->groupBy(['user_id'])
            ->orderBy(['user_id' => SORT_ASC])
            ->asArray()
            ->all();

        return $Stat;
    }
}