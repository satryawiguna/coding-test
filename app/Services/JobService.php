<?php

namespace App\Services;

use App\Core\Request\ListDataRequest;
use App\Core\Request\ListSearchDataRequest;
use App\Core\Request\ListSearchPageDataRequest;
use App\Core\Response\BasicResponse;
use App\Core\Response\GenericListResponse;
use App\Core\Response\GenericListSearchPageResponse;
use App\Core\Response\GenericListSearchResponse;
use App\Core\Response\GenericObjectResponse;
use App\Enums\HttpResponseType;
use App\Exceptions\ResponseNotFoundException;
use App\Http\Requests\Job\JobStoreRequest;
use App\Repositories\Contracts\IJobRepository;
use App\Services\Contracts\IJobService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JobService extends BaseService implements IJobService
{
    public IJobRepository $_jobRepository;

    public function __construct(IJobRepository $jobRepository)
    {
        $this->_jobRepository = $jobRepository;
    }

    public function getAllJob(ListDataRequest $request): GenericListResponse
    {
        $response = new GenericListResponse();

        try {
            $jobs = $this->_jobRepository->allJob($request);

            $response = $this->setGenericListResponse($response,
                $jobs,
                'SUCCESS',
                HttpResponseType::SUCCESS);

        } catch (QueryException $ex) {
            DB::rollBack();

            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                $ex->getMessage());

            Log::error("Invalid query", $response->getMessageResponseError());

        } catch (Exception $ex) {
            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                $ex->getMessage());

            Log::error("Invalid get all jobs", $response->getMessageResponseError());
        }

        return $response;
    }

    public function getAllSearchJob(ListSearchDataRequest $request): GenericListSearchResponse
    {
        $response = new GenericListSearchResponse();

        try {
            $users = $this->_jobRepository->allSearchJob($request);

            $response = $this->setGenericListSearchResponse($response,
                $users,
                $users->count(),
                'SUCCESS',
                HttpResponseType::SUCCESS);

        } catch (QueryException $ex) {
            DB::rollBack();

            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                $ex->getMessage());

            Log::error("Invalid query", $response->getMessageResponseError());

        } catch (Exception $ex) {
            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                $ex->getMessage());

            Log::error("Invalid get all jobs by search", $response->getMessageResponseError());
        }

        return $response;
    }

    public function getAllSearchPageJob(ListSearchPageDataRequest $request): GenericListSearchPageResponse
    {
        $response = new GenericListSearchPageResponse();

        try {
            $users = $this->_jobRepository->allSearchPageJob($request);

            $response = $this->setGenericListSearchPageResponse($response,
                $users->getCollection(),
                $users->count(),
                ["perPage" => $users->perPage(), "currentPage" => $users->currentPage()],
                'SUCCESS',
                HttpResponseType::SUCCESS);

        } catch (QueryException $ex) {
            DB::rollBack();

            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::BAD_REQUEST,
                $ex->getMessage());

            Log::error("Invalid query", $response->getMessageResponseError());

        } catch (Exception $ex) {
            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                $ex->getMessage());

            Log::error("Invalid get jobs by search page", $response->getMessageResponseError());
        }

        return $response;
    }

    public function getJob(int $id): GenericObjectResponse
    {
        $response = new GenericObjectResponse();

        try {
            $job = $this->_jobRepository->findJobById($id);

            if (!$job) {
                throw new ResponseNotFoundException("Job not found");
            }

            $response = $this->setGenericObjectResponse($response,
                $job,
                'SUCCESS',
                HttpResponseType::SUCCESS);

        } catch (ResponseNotFoundException $ex) {
            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::NOT_FOUND,
                $ex->getMessage());

            Log::error("User $id not found", $response->getMessageResponseError());

        } catch (Exception $ex) {
            $response = $this->setMessageResponse($response,
                'ERROR',
                HttpResponseType::INTERNAL_SERVER_ERROR,
                $ex->getMessage());

            Log::error("Invalid get job $id", $response->getMessageResponseError());
        }

        return $response;
    }

    public function storeJob(JobStoreRequest $request): GenericObjectResponse
    {
        // TODO: Implement storeJob() method.
    }

    public function updateJob(JobStoreRequest $request): GenericObjectResponse
    {
        // TODO: Implement updateJob() method.
    }

    public function destroyJob(string $id): BasicResponse
    {
        // TODO: Implement destroyJob() method.
    }


}
