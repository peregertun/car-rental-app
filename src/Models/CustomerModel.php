<?php

namespace CarRental\Models;

use PDO;

class CustomerModel extends AbstractModel
{
  public function customerList()
  {
    $customerRows = $this->db->query("SELECT * FROM Customers ORDER BY `customerNumber` DESC");

    $customers = [];
    foreach ($customerRows as $customerRow) {
      $customerNumber = htmlspecialchars($customerRow["customerNumber"]);
      $customerName = htmlspecialchars($customerRow["customerName"]);
      $customerSocialSecurityNumber = htmlspecialchars($customerRow["customerSocialSecurityNumber"]);
      $customerAddress = htmlspecialchars($customerRow["customerAddress"]);
      $customerPostCode = htmlspecialchars($customerRow["customerPostCode"]);
      $customerPhoneNumber = htmlspecialchars($customerRow["customerPhoneNumber"]);
      $isRenting = htmlspecialchars($customerRow["isRenting"]);
      $customer = [
        "customerNumber" => $customerNumber,
        "customerName" => $customerName,
        "customerSocialSecurityNumber" => $customerSocialSecurityNumber,
        "customerAddress" => $customerAddress,
        "customerPostCode" => $customerPostCode,
        "customerPhoneNumber" => $customerPhoneNumber,
        "isRenting" => $isRenting
      ];
      $customers[] = $customer;
    }
    return $customers;
  }

  public function addCustomer(
    $customerName,
    $customerSocialSecurityNumber,
    $customerAddress,
    $customerPostCode,
    $customerPhoneNumber
  ) {
    $customersQuery = "INSERT INTO `Customers` (`customerName`, `customerSocialSecurityNumber`, `customerAddress`, `customerPostCode`, `customerPhoneNumber`)" .
      "VALUES(:customerName, :customerSocialSecurityNumber, :customerAddress, :customerPostCode, :customerPhoneNumber)";
    $customersStatement = $this->db->prepare($customersQuery);
    $customersStatement->execute([
      "customerName" => $customerName,
      "customerSocialSecurityNumber" => $customerSocialSecurityNumber,
      "customerAddress" => $customerAddress,
      "customerPostCode" => $customerPostCode,
      "customerPhoneNumber" => $customerPhoneNumber
    ]);
    if (!$customersStatement) die("Fatal error."); // $this->db->errorInfo());
    $customerNumber = $this->db->lastInsertId();
    return $customerNumber;
  }

  public function editCustomer($customerNumber, $customerName, $customerSocialSecurityNumber, $customerNewAddress, $customerNewPostCode, $customerNewPhoneNumber)
  {
    $customersQuery = "UPDATE `Customers` SET `customerNumber` = $customerNumber, `customerName` = :customerName, `customerSocialSecurityNumber` = :customerSocialSecurityNumber, `customerAddress` = :customerAddress, 
    `customerPostCode` = :customerPostCode, `customerPhoneNumber` = :customerPhoneNumber WHERE `Customers`.`customerNumber` = :customerNumber";
    $customersStatement = $this->db->prepare($customersQuery);
    $customersParameters = [
      "customerNumber" => $customerNumber,
      "customerName" => $customerName,
      "customerSocialSecurityNumber" => $customerSocialSecurityNumber,
      "customerAddress" => $customerNewAddress,
      "customerPostCode" => $customerNewPostCode,
      "customerPhoneNumber" => $customerNewPhoneNumber
    ];
    $customersResult = $customersStatement->execute($customersParameters);
  }

  public function removeCustomer($customerNumber)
  {
    $customersQuery = "DELETE FROM Customers WHERE customerNumber = :customerNumber";
    $customersStatement = $this->db->prepare($customersQuery);
    $customersResult = $customersStatement->execute(["customerNumber" => $customerNumber]);

    $historyQuery = "UPDATE `History` SET `rentedByCustomer` = 'customer has been removed' WHERE `rentedByCustomer` = :customerNumber";
    $historyStatement = $this->db->prepare($historyQuery);
    $historyStatement->execute(["customerNumber" => $customerNumber]);
  }
}
