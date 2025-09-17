<?php

namespace uranium\core;

use uranium\model\CronQueueModel;

class JobManager{

    /* Entry for the cron run commnd */
    public static function runCron():Void {
        $jobs = self::getjobs();
        foreach($jobs as $job){
            $componentName = $job["component"];
            echo "Running ".$componentName.PHP_EOL;
            self::runComponent($componentName);
            if($job["reschedule"] == 1){
                self::rescheduleJob($job);
            }else{
                $queueModel = new CronQueueModel();
                $queueModel->where("id", $job["id"])->delete();
            };
        };
    }

    /** Add the job back to the database if reschedule */
    public static function rescheduleJob(Array $job):Void {
        $queueModel = new CronQueueModel();
        $queueModel->rows[] = $job;
        $queueModel->save();
    }

    /**
     * Get jobs from the cronQueue table
     * @return Array of CronQueue model items
     */
    public static function getJobs():Array {
        $queueModel = new CronQueueModel();
        $config = ConfigHandler::getInstance();
        $limit =  $config->getValue("cron_job_limiter");
        if(is_null($limit)){
            $limit = 20;
        };
        $jobs = $queueModel->limit((int) $limit)->get(true)->getResults();
        return $jobs;
    }

    /**
     * Run the JOB components run method
     * @param name of the component class in the job directory
     */
    public static function runComponent(String $componentName):Void {
        $componentName = "\\uranium\\job\\".$componentName;
        $componentClass = new $componentName();
        $componentClass::run();
    }
};
