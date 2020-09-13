<?php

namespace CarRental\Controllers;

use CarRental\Models\CustomerModel;

class CustomerController extends AbstractController
{
  public function customerList(): string
  {
    $customerModel = new CustomerModel($this->db);
    $customers = $customerModel->customerList();
    $properties = ["customers" => $customers];
    return $this->render("Customers.twig", $properties);
  }

  public function addCustomer()
  {
    return $this->render("AddCustomer.twig", []);
  }

  public function customerAdded()
  {
    $form = $this->request->getForm();
    $customerName = $form["customerName"];
    $customerSocialSecurityNumber = $form["customerSocialSecurityNumber"];
    $customerAddress = $form["customerAddress"];
    $customerPostCode = $form["customerPostCode"];
    $customerPhoneNumber = $form["customerPhoneNumber"];
    $customerModel = new CustomerModel($this->db);
    $customerNumber = $customerModel->addCustomer($customerName, $customerSocialSecurityNumber, $customerAddress, $customerPostCode, $customerPhoneNumber);
    $properties = [
      "customerNumber" => $customerNumber,
      "customerName" => $customerName,
      "customerSocialSecurityNumber" => $customerSocialSecurityNumber,
      "customerAddress" => $customerAddress,
      "customerPostCode" => $customerPostCode,
      "customerPhoneNumber" => $customerPhoneNumber
    ];
    return $this->render("CustomerAdded.twig", $properties);
  }

  public function editCustomer($customerNumber, $customerName, $customerSocialSecurityNumber, $customerAddress, $customerPostCode, $customerPhoneNumber)
  {
    $properties = [
      "customerNumber" => $customerNumber,
      "customerName" => $customerName,
      "customerSocialSecurityNumber" => $customerSocialSecurityNumber,
      "customerAddress" => $customerAddress,
      "customerPostCode" => $customerPostCode,
      "customerPhoneNumber" => $customerPhoneNumber
    ];
    return $this->render("EditCustomer.twig", $properties);
  }

  public function customerEdited($customerNumber, $customerName, $customerSocialSecurityNumber, $customerAddress, $customerPostCode, $customerPhoneNumber)
  {
    $form = $this->request->getForm();
    $customerNewName = $form["customerName"];
    $customerNewAddress = $form["customerAddress"];
    $customerNewPostCode = $form["customerPostCode"];
    $customerNewPhoneNumber = $form["customerPhoneNumber"];

    $customerModel = new CustomerModel($this->db);
    $customerModel->editCustomer($customerNumber, $customerNewName, $customerSocialSecurityNumber, $customerNewAddress, $customerNewPostCode, $customerNewPhoneNumber);
    $properties = [
      "customerNumber" => $customerNumber,
      "customerName" => $customerNewName,
      "customerSocialSecurityNumber" => $customerSocialSecurityNumber,
      "customerAddress" => $customerNewAddress,
      "customerPostCode" => $customerNewPostCode,
      "customerPhoneNumber" => $customerNewPhoneNumber
    ];
    return $this->render("CustomerEdited.twig", $properties);
  }

  public function removeCustomer($customerNumber, $customerName, $customerSocialSecurityNumber, $customerAddress, $customerPostCode, $customerPhoneNumber)
  {
    $customerModel = new CustomerModel($this->db);
    $customerModel->removeCustomer($customerNumber);
    $properties = [
      "customerNumber" => $customerNumber,
      "customerName" => $customerName,
      "customerSocialSecurityNumber" => $customerSocialSecurityNumber,
      "customerAddress" => $customerAddress,
      "customerPostCode" => $customerPostCode,
      "customerPhoneNumber" => $customerPhoneNumber
    ];
    return $this->render("CustomerRemoved.twig", $properties);
  }
}
