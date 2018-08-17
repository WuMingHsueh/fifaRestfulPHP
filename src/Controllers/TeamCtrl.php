<?php
namespace FifaRestfulPHP\Controllers;

use FifaRestfulPHP\Models\TeamDetail;
use FifaRestfulPHP\Service\Auth;

class TeamCtrl
{
    private $authService ;

    public function __construct(Auth $auth = null)
    {
        $this->authService = $auth;
    }
    
    public function teamList()
    {
		$data = TeamDetail::on()
				->select("fifa_code as code",
						"name",
						"fifa_ranking as rank",
						"flag_url as flagUrl")
				->orderBy("rank")
				->get(); 
		return \json_encode($data, JSON_UNESCAPED_SLASHES);
    }
    
    public function teamDetail($pathInfo)
    {
        if (!$this->authService->isLoggined()){
			$message = json_encode(['msg' => "Please login to access this information"]);
    		throw new \Exception($message, 500);
		}

		$dbResult = TeamDetail::where('fifa_code', $pathInfo->code)
					->select("logo_url as logoUrl",
							"fifa_code as fifaCode",
							"fifa_ranking as fifaRanking",
							"name",
							"nickname",
							"association",
							"head_coach as headCoach",
							"captain")
					->first();
		return \json_encode($dbResult, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
