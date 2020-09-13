<?php

namespace CarRental\Models;

use DateTime;
use PDO;

class CarModel extends AbstractModel
{
  public function carList()
  {
    $carRows = $this->db->query("SELECT * FROM Cars ORDER BY `carNumber` DESC");

    $cars = [];
    foreach ($carRows as $carRow) {
      $carNumber = htmlspecialchars($carRow["carNumber"]);
      $carLicenseNumber = htmlspecialchars($carRow["carLicenseNumber"]);
      $carBrand = htmlspecialchars($carRow["carBrand"]);
      $carYear = htmlspecialchars($carRow["carYear"]);
      $carColor = htmlspecialchars($carRow["carColor"]);
      $carPrice = htmlspecialchars($carRow["carPrice"]);
      $isVacant = htmlspecialchars($carRow["isVacant"]);
      $pickUpTime = htmlspecialchars($carRow["pickUpTime"]);
      $car = [
        "carNumber" => $carNumber,
        "carLicenseNumber" => $carLicenseNumber,
        "carBrand" => $carBrand,
        "carYear" => $carYear,
        "carColor" => $carColor,
        "carPrice" => $carPrice,
        "isVacant" => $isVacant,
        "pickUpTime" => $pickUpTime
      ];
      $cars[] = $car;
    }
    return $cars;
  }

  public function getInfo()
  {
    $query = "SELECT * FROM Colors";
    $statement = $this->db->prepare($query);
    $statement->execute();
    $colors = $statement->fetchAll();
    return $colors;
  }

  public function addCar($carLicenseNumber, $carBrand, $carYear, $carColor, $carPrice)
  {
    $carsQuery = "INSERT INTO `Cars` (`carLicenseNumber`, `carBrand`, `carYear`, `carColor`, `carPrice`)
    VALUES (:carLicenseNumber, :carBrand, :carYear, :carColor, :carPrice)";
    $carsStatement = $this->db->prepare($carsQuery);
    $carsStatement->execute([
      "carLicenseNumber" => $carLicenseNumber,
      "carBrand" => $carBrand,
      "carYear" => $carYear,
      "carColor" => $carColor,
      "carPrice" => $carPrice
    ]);

    $carNumber = $this->db->lastInsertId();
    return $carNumber;
  }

  public function editCar($carNumber, $carNewLicenseNumber, $carNewBrand, $carNewYear, $carNewColor, $carNewPrice)
  {
    $carsQuery = "UPDATE `Cars` SET `carNumber` = '$carNumber', `carLicenseNumber` = :carLicenseNumber, `carBrand` = :carBrand, 
    `carYear` = :carYear, `carColor` = :carColor, `carPrice` = :carPrice WHERE `Cars`.`carNumber` = :carNumber";
    $carsStatement = $this->db->prepare($carsQuery);
    $carsParameters = [
      "carNumber" => $carNumber,
      "carLicenseNumber" => $carNewLicenseNumber,
      "carBrand" => $carNewBrand,
      "carYear" => $carNewYear,
      "carColor" => $carNewColor,
      "carPrice" => $carNewPrice
    ];
    $carsResult = $carsStatement->execute($carsParameters);
  }

  public function removeCar($carNumber)
  {
    $carsQuery = "DELETE FROM Cars WHERE carNumber = :carNumber";
    $carsStatement = $this->db->prepare($carsQuery);
    $carsResult = $carsStatement->execute(["carNumber" => $carNumber]);

    $historyQuery = "UPDATE `History` SET `carNumber` = 'car has been removed' WHERE `carNumber` = :carNumber";
    $historyStatement = $this->db->prepare($historyQuery);
    $historyStatement->execute(["carNumber" => $carNumber]);
  }

  public function getCustomers()
  {
    $customerRows = $this->db->query("SELECT * FROM Customers ORDER BY `customerNumber` DESC");

    $customers = [];
    foreach ($customerRows as $customerRow) {
      $customerNumber = htmlspecialchars($customerRow["customerNumber"]);
      $customerName = htmlspecialchars($customerRow["customerName"]);
      $customer = [
        "customerNumber" => $customerNumber,
        "customerName" => $customerName
      ];
      $customers[] = $customer;
    }
    return $customers;
  }

  public function rentCar($carNumber, $customerNumber)
  {
    date_default_timezone_set("Europe/Stockholm");
    $pickUpTime = (new DateTime('now'))->format('Y-m-d H:i:s');

    $carsQuery = "UPDATE `Cars` SET `isVacant` = '0', `pickUpTime` = '$pickUpTime', `rentedBy` = '$customerNumber'  
    WHERE `carNumber` = :carNumber";
    $carsStatement = $this->db->prepare($carsQuery);
    $carsResult = $carsStatement->execute(["carNumber" => $carNumber]);

    $historyQuery = "INSERT INTO `History` (`carNumber`, `rentedByCustomer`, `pickUpTime`) 
    VALUES ('$carNumber', '$customerNumber', '$pickUpTime')";
    $historyStatement = $this->db->prepare($historyQuery);

    $customerQuery = "UPDATE `Customers` SET `isRenting` = isRenting +1 WHERE `customerNumber` = '$customerNumber'";
    $customerStatement = $this->db->prepare($customerQuery);
    $customerStatement->execute();

    $rentalParameters = [
      "carNumber" => $carNumber,
      "customerNumber" => $customerNumber
    ];

    $historyStatement->execute($rentalParameters);
  }

  public function returnCar($carNumber)
  {
    date_default_timezone_set("Europe/Stockholm");
    $returnTime = (new DateTime('now'))->format('Y-m-d H:i:s');

    $carsQuery = "UPDATE `Cars` SET `isVacant` = '1', `returnTime` = '$returnTime' WHERE `carNumber` = :carNumber";
    $carsStatement = $this->db->prepare($carsQuery);
    $carsStatement->execute(["carNumber" => $carNumber]);

    $findPriceQuery = "SELECT `carPrice` FROM `CARS` WHERE `carNumber` = '$carNumber'";
    $findPriceStatement = $this->db->prepare($findPriceQuery);

    $findPriceStatement->execute();
    $result = $findPriceStatement->fetchAll();
    $dayCost = $result[0][0];

    $findDatesQuery = "SELECT `pickUpTime`, `returnTime` FROM `HISTORY` WHERE `carNumber` = '$carNumber' ORDER BY pickUpTime DESC LIMIT 1";
    $findDatesStatement = $this->db->prepare($findDatesQuery);

    $findDatesStatement->execute();
    $datesResult = $findDatesStatement->fetchAll();
    $pickUp = $datesResult[0]["pickUpTime"];
    // $pickUp = "2020-01-15 21:07:46";   FÖR ATT KOLLA MED ANNAT DATUM
    $return = $returnTime;

    $pickUp = new DateTime($pickUp);
    $return = new DateTime($return);

    $interval = $pickUp->diff($return);

    $minutesRented = $interval->i;
    $hoursRented = $interval->h;
    $daysRented = $interval->d;

    if ($minutesRented >= 0 || $hoursRented >= 0) {
      $daysRented++;
    }

    $finalprice = ($daysRented * $dayCost);

    $historyQuery = "UPDATE `History` SET `returnTime` = '$returnTime', `carPrice` = '$finalprice', `daysRented` = '$daysRented' WHERE `carNumber` = :carNumber ORDER BY bookingNumber DESC LIMIT 1";
    $historyStatement = $this->db->prepare($historyQuery);
    $historyStatement->execute(["carNumber" => $carNumber]);

    $findCustomerQuery = "SELECT `rentedBy` FROM `CARS` WHERE `carNumber` = '$carNumber'";
    $findCustomerStatement = $this->db->prepare($findCustomerQuery);

    $findCustomerStatement->execute();
    $rentedByCustomers = $findCustomerStatement->fetchAll();
    $rentedBy = $rentedByCustomers[0][0];

    $customerQuery = "UPDATE `Customers` SET `isRenting` = isRenting -1  WHERE `customerNumber` = '$rentedBy'";
    $customerStatement = $this->db->prepare($customerQuery);
    $customerStatement->execute();
  }

  public function historyList()
  {
    $bookingRows = $this->db->query("SELECT * FROM `History` ORDER BY `bookingNumber` DESC");

    $bookings = [];
    foreach ($bookingRows as $bookingRow) {
      $bookingNumber = htmlspecialchars($bookingRow["bookingNumber"]);
      $carNumber = htmlspecialchars($bookingRow["carNumber"]);
      $customerNumber = htmlspecialchars($bookingRow["rentedByCustomer"]);
      $pickUpTime = htmlspecialchars($bookingRow["pickUpTime"]);
      $returnTime = htmlspecialchars($bookingRow["returnTime"]);
      $daysRented = htmlspecialchars($bookingRow["daysRented"]);
      $carPrice = htmlspecialchars($bookingRow["carPrice"]);

      $booking = [
        "bookingNumber" => $bookingNumber,
        "carNumber" => $carNumber,
        "customerNumber" => $customerNumber,
        "pickUpTime" => $pickUpTime,
        "returnTime" => $returnTime,
        "daysRented" => $daysRented,
        "carPrice" => $carPrice
      ];

      $bookings[] = $booking;
    }

    return $bookings;
  }

  public function home()
  {
    echo "hello";
  }
}
