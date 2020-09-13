<?php

namespace CarRental\Controllers;

use CarRental\Models\CarModel;

class CarController extends AbstractController
{
  public $eventNumber;

  public function carList(): string
  {
    $carsModel = new CarModel($this->db);
    $cars = $carsModel->carList();
    $properties = ["cars" => $cars];
    return $this->render("Cars.twig", $properties);
  }

  public function addCar()
  {
    $carsModel = new CarModel($this->db);
    $colors = $carsModel->getInfo();
    $colorArray = ["colors" => $colors];

    return $this->render("AddCar.twig", $colorArray);
  }

  public function carAdded()
  {
    $form = $this->request->getForm();
    $carLicenseNumber = $form["carLicenseNumber"];
    $carBrand = $form["carBrand"];
    $carYear = $form["carYear"];
    $carColor = $form["carColor"];
    $carPrice = $form["carPrice"];

    $carsModel = new CarModel($this->db);
    $carNumber = $carsModel->addCar($carLicenseNumber, $carBrand, $carYear, $carColor, $carPrice);
    $properties = [
      "carNumber" => $carNumber,
      "carLicenseNumber" => $carLicenseNumber,
      "carBrand" => $carBrand,
      "carYear" => $carYear,
      "carColor" => $carColor,
      "carPrice" => $carPrice
    ];

    return $this->render("CarAdded.twig", $properties);
  }

  public function editCar($carNumber, $carLicenseNumber, $carBrand, $carYear, $carColor, $carPrice)
  {
    $carsModel = new CarModel($this->db);

    $colors = $carsModel->getInfo();
    $properties = [
      "carNumber" => $carNumber,
      "carLicenseNumber" => $carLicenseNumber,
      "carBrand" => $carBrand,
      "carYear" => $carYear,
      "carColor" => $carColor,
      "carPrice" => $carPrice,
      "colors" => $colors
    ];

    return $this->render("EditCar.twig", $properties);
  }

  public function carEdited($carNumber, $carLicenseNumber)
  {
    $form = $this->request->getForm();
    $carNewBrand = $form["carBrand"];
    $carNewYear = $form["carYear"];
    $carNewColor = $form["carColor"];
    $carNewPrice = $form["carPrice"];

    $carModel = new CarModel($this->db);
    $carModel->editCar($carNumber, $carLicenseNumber, $carNewBrand, $carNewYear, $carNewColor, $carNewPrice);
    $properties = [
      "carNumber" => $carNumber,
      "carLicenseNumber" => $carLicenseNumber,
      "carBrand" => $carNewBrand,
      "carYear" => $carNewYear,
      "carColor" => $carNewColor,
      "carPrice" => $carNewPrice
    ];
    return $this->render("CarEdited.twig", $properties);
  }

  public function removeCar($carNumber, $carLicenseNumber, $carBrand, $carYear, $carColor, $carPrice)
  {
    $carModel = new CarModel($this->db);
    $carModel->removeCar($carNumber);
    $properties = [
      "carNumber" => $carNumber,
      "carLicenseNumber" => $carLicenseNumber,
      "carBrand" => $carBrand,
      "carYear" => $carYear,
      "carColor" => $carColor,
      "carPrice" => $carPrice
    ];
    return $this->render("CarRemoved.twig", $properties);
  }

  public function getCustomers($carNumber, $carBrand)
  {
    $carModel = new CarModel($this->db);
    $customers = $carModel->getCustomers($carNumber);
    $properties = [
      "customers" => $customers,
      "carNumber" => $carNumber,
      "carBrand" => $carBrand,
    ];

    return $this->render("StartRent.twig", $properties);
  }

  public function rentCar($carNumber, $customerNumber)
  {
    $form = $this->request->getForm();
    $customerNumber = $form["customerNumber"];

    $properties = [
      "carNumber" => $carNumber,
      "customerNumber" => $customerNumber
    ];

    return $this->render("RentCar.twig", $properties);
  }

  public function carRented($carNumber, $customerNumber)
  {
    // $form = $this->request->getForm();
    $carModel = new CarModel($this->db);
    $carModel->rentCar($carNumber, $customerNumber);

    $properties = [
      "carNumber" => $carNumber,
      "customerNumber" => $customerNumber
    ];
    return $this->render("CarRented.twig", $properties);
  }

  public function returnCar($carNumber, $isVacant, $carBrand)
  {
    $carModel = new CarModel($this->db);
    $carModel->returnCar($carNumber);
    $properties = [
      "carNumber" => $carNumber,
      "isVacant" => $isVacant,
      "carBrand" => $carBrand
    ];
    return $this->render("CarReturned.twig", $properties);
  }

  public function historyList(): string
  {
    $carsModel = new CarModel($this->db);
    $bookings = $carsModel->historyList();
    $properties = ["bookings" => $bookings];

    return $this->render("History.twig", $properties);
  }

  public function home(): string
  {
    $carsModel = new CarModel($this->db);
    $carsModel->home();
    $properties = [];
    return $this->render("Hello.twig", $properties);
  }
}
