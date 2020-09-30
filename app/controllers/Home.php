<?php

class Home extends Controller
{
	public function index()
	{
		$data["title-page"] = "Home";

		$this->view("templates/head", $data);
		echo "Hello World!";
		$this->view("templates/footer");
	}
}
