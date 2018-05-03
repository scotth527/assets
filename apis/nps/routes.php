<?php

require('../../vendor_static/breathecode-api/BreatheCodeAPI.php');

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Carbon\Carbon;
use \BreatheCode\BCWrapper;

require('../../vendor_static/ActiveCampaign/ACAPI.php');

BCWrapper::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
BCWrapper::setToken(BREATHECODE_TOKEN);

\AC\ACAPI::start(AC_API_KEY);
\AC\ACAPI::setupEventTracking('25182870', AC_EVENT_KEY);

function addAPIRoutes($api){

	$api->get('/responses', function (Request $request, Response $response, array $args) use ($api) {
		$content = $api->db['sqlite']->response();
	    return $response->withJson($content);
	});
	
	$api->get('/student/{user_id}', function (Request $request, Response $response, array $args) use ($api) {
		
		if(empty($args['user_id'])) throw new Exception('Invalid param user_id', 500);
		
		$student = BCWrapper::getStudent(['student_id'=>$args['user_id']]);
	    return $response->withJson($student);
	});
	
	$api->get('/responses/user/{user_id}', function(Request $request, Response $response, array $args) use ($api) {
        
		if(empty($args['user_id'])) throw new Exception('Invalid param user_id', 500);
		
		$row = $api->db['sqlite']->response()
			->where('user_id',$args['user_id']);

		if(!$row) $row = (object) [];
		return $response->withJson($row);	
	});

	$api->get('/response', function(Request $request, Response $response, array $args) use ($api) {
		$responses = $api->db['sqlite']->response()->fetchAll();
		return $response->withJson($responses);	
	});
	
	$api->put('/response', function(Request $request, Response $response, array $args) use ($api) {

        $parsedBody = $request->getParsedBody();
        if(empty($parsedBody['score'])) throw new Exception('Invalid param quiz_id');
        if(empty($parsedBody['email'])) throw new Exception('Invalid param email');
        if(empty($parsedBody['user_id'])) throw new Exception('Invalid param user_id');
        if(empty($parsedBody['cohort_slug'])) throw new Exception('Invalid param cohort_slug');
        
		$answers = $api->db['sqlite']->response()
			->where('user_id',$parsedBody['user_id'])->fetchAll();
		foreach($answers as $ans){
			$now = Carbon::now();
			$day = Carbon::createFromFormat('Y-m-d H:i:s', $ans->created_at);
			if($now->diffInHours($day) < 24) throw new Exception('You have already answered');
		} 
        
        $score = $parsedBody['score'];
        $email = $parsedBody['email'];
        $userId = $parsedBody['user_id'];
        $cohort = $parsedBody['cohort_slug'];
        $comment = (!empty($parsedBody['comment'])) ? $parsedBody['cohort_slug'] : null;
		
		$row = $api->db['sqlite']->createRow( 'response', $properties = array(
			'score' => $score,
			'user_id' => $userId,
			'cohort_slug' => $cohort,
			'comment' => $cohort,
			'created_at' => date("Y-m-d H:i:s")
		) );
		
		$row->save();
		
		try{
        	$contact = \AC\ACAPI::trackEvent($email, 'nps_survey_answered');
		}
		catch(Exception $e)
		{
			$api->sendMail(
				ADMIN_EMAIL, 
				'API Error: Event tracking for nps_survey_answered',
				'There has been an error trying to register the nps_survey_answered event in active campaign for the user '.$email
			);
		}
        return $response->withJson($row);
	});
	
	return $api;
}