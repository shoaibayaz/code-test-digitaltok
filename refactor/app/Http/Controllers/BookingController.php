<?php

namespace DTApi\Http\Controllers;

use DTApi\Http\Requests;
use DTApi\Models\Distance;
use DTApi\Models\Job;
use DTApi\Repository\BookingRepository;
use Illuminate\Http\Request;

/**
 * Class BookingController
 * @package DTApi\Http\Controllers
 */
class BookingController extends Controller
{
    
    /**
     * @var BookingRepository
     */
    protected $repository;
    
    /**
     * BookingController constructor.
     * @param BookingRepository $bookingRepository
     */
    public function __construct(BookingRepository $bookingRepository)
    {
        $this->repository = $bookingRepository;
    }
    
    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $response = [];
        if ($user_id = $request->get('user_id')) {
            $response = $this->repository->getUsersJobs($user_id);
        } elseif ($request->user()->user_type == env('ADMIN_ROLE_ID') || $request->user()->user_type == env('SUPERADMIN_ROLE_ID')) {
            $response = $this->repository->getAll($request);
        }
        
        return response($response);
    }
    
    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return response($this->repository->with('translatorJobRel.user')->find($id));
    }
    
    /**
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        return response($this->repository->store($request->user(), $request->all()));
    }
    
    /**
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function update($id, Request $request)
    {
        return response($this->repository->updateJob($id, $request->except(['_token', 'submit']), $request->user()));
    }
    
    /**
     * @param Request $request
     * @return mixed
     */
    public function immediateJobEmail(Request $request)
    {
        return response($this->repository->storeJobEmail($request->all()));
    }
    
    /**
     * @param Request $request
     * @return mixed
     */
    public function getHistory(Request $request)
    {
        if ($userId = $request->get('user_id')) {
            return response($this->repository->getUsersJobsHistory($userId, $request->get('page', 1)));
        }
        
        return null;
    }
    
    /**
     * @param Request $request
     * @return mixed
     */
    public function acceptJob(Request $request)
    {
        return response($this->repository->acceptJob($request->all(), $request->user()));
    }
    
    public function acceptJobWithId(Request $request)
    {
        return response($this->repository->acceptJobWithId($request->get('job_id'), $request->user()));
    }
    
    /**
     * @param Request $request
     * @return mixed
     */
    public function cancelJob(Request $request)
    {
        return response($this->repository->cancelJobAjax($request->all(), $request->user()));
    }
    
    /**
     * @param Request $request
     * @return mixed
     */
    public function endJob(Request $request)
    {
        return response($this->repository->endJob($request->all()));
        
    }
    
    public function customerNotCall(Request $request)
    {
        return response($this->repository->customerNotCall($request->all()));
        
    }
    
    /**
     * @param Request $request
     * @return mixed
     */
    public function getPotentialJobs(Request $request)
    {
        return response($this->repository->getPotentialJobs($request->user()));
    }
    
    public function distanceFeed(Request $request)
    {
        
        $distance = $request->get('distance', '');
        $jobId = $request->get('jobid', '');
        $time = $request->get('time', '');
        $session = $request->get('session_time', '');
        $flagged = $request->get('flagged', '') == 'true' ? 'yes' : 'no';
        $manuallyHandled = $request->get('manually_handled', '') == 'true' ? 'yes' : 'no';
        $byAdmin = $request->get('by_admin', '') == 'true' ? 'yes' : 'no';
        $adminComment = $request->get('admincomment', '');
        
        if ($flagged == 'yes') {
            if ($adminComment == '') return "Please, add comment";
        }
        
        if (!empty($jobId)) {
            if ($time || $distance) {
                Distance::where('job_id', '=', $jobId)->update(['distance' => $distance, 'time' => $time]);
            }
            
            $this->repository->update(
                $jobId,
                ['admin_comments' => $adminComment, 'flagged' => $flagged, 'session_time' => $session, 'manuallyHandled' => $manuallyHandled, 'by_admin' => $byAdmin]
            );
        }
        
        return response('Record updated!');
    }
    
    public function reopen(Request $request)
    {
        return response($this->repository->reopen($request->all()));
    }
    
    public function resendNotifications(Request $request)
    {
        $job = $this->repository->find($request->get('jobid'));
        $jobData = $this->repository->jobToData($job);
        $this->repository->sendNotificationTranslator($job, $jobData, '*');
        
        return response(['success' => 'Push sent']);
    }
    
    /**
     * Sends SMS to Translator
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function resendSMSNotifications(Request $request)
    {
        $job = $this->repository->find($request->get('jobid'));
        try {
            $this->repository->sendSMSNotificationToTranslator($job);
            return response(['success' => 'SMS sent']);
        } catch (\Exception $e) {
            return response(['success' => $e->getMessage()]);
        }
    }
    
}
