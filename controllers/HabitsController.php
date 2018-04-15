<?php

namespace Grocy\Controllers;

use \Grocy\Services\HabitsService;

class HabitsController extends BaseController
{
	public function __construct(\Slim\Container $container)
	{
		parent::__construct($container);
		$this->HabitsService = new HabitsService();
	}

	protected $HabitsService;

	public function Overview(\Slim\Http\Request $request, \Slim\Http\Response $response, array $args)
	{
		$nextHabitTimes = array();
		foreach($this->Database->habits() as $habit)
		{
			$nextHabitTimes[$habit->id] = $this->HabitsService->GetNextHabitTime($habit->id);
		}

		return $this->AppContainer->view->render($response, 'habitsoverview', [
			'habits' => $this->Database->habits(),
			'currentHabits' => $this->HabitsService->GetCurrentHabits(),
			'nextHabitTimes' => $nextHabitTimes
		]);
	}

	public function TrackHabitExecution(\Slim\Http\Request $request, \Slim\Http\Response $response, array $args)
	{
		return $this->AppContainer->view->render($response, 'habittracking', [
			'habits' => $this->Database->habits()
		]);
	}

	public function HabitsList(\Slim\Http\Request $request, \Slim\Http\Response $response, array $args)
	{
		return $this->AppContainer->view->render($response, 'habits', [
			'habits' => $this->Database->habits()
		]);
	}

	public function HabitEditForm(\Slim\Http\Request $request, \Slim\Http\Response $response, array $args)
	{
		if ($args['habitId'] == 'new')
		{
			return $this->AppContainer->view->render($response, 'habitform', [
				'periodTypes' => GetClassConstants('\Grocy\Services\HabitsService'),
				'mode' => 'create'
			]);
		}
		else
		{
			return $this->AppContainer->view->render($response, 'habitform', [
				'habit' =>  $this->Database->habits($args['habitId']),
				'periodTypes' => GetClassConstants('\Grocy\Services\HabitsService'),
				'mode' => 'edit'
			]);
		}
	}
}