DROP DATABASE IF EXISTS Bank;
CREATE DATABASE Bank;
USE Bank;

CREATE TABLE Customers (customerNumber INTEGER NOT NULL AUTO_INCREMENT KEY,
                        customerName VARCHAR(256));

CREATE TABLE Accounts (accountNumber INTEGER NOT NULL AUTO_INCREMENT KEY,
                       customerNumber INTEGER NOT NULL,
                       FOREIGN KEY (customerNumber) REFERENCES Customers(customerNumber));

CREATE TABLE Events (accountNumber INTEGER NOT NULL,
                     time TIMESTAMP,
                     amount REAL, -- FLOAT
                     FOREIGN KEY (accountNumber) REFERENCES Accounts(accountNumber));

INSERT INTO Customers(customerName)
  VALUES ('Adam Bertilsson'), ('Bertil Ceasarsson'),  ('Ceasar Davidsson'),
         ('David Eriksson'), ('Erik Filipsson'),  ('Filip Gustavsson');

INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'Adam Bertilsson';
INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'Bertil Ceasarsson';
INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'Adam Bertilsson';
INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'Bertil Ceasarsson';
INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'Adam Bertilsson';
INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'Bertil Ceasarsson';
INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'Ceasar Davidsson';
INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'David Eriksson';
INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'Ceasar Davidsson';
INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'David Eriksson';
INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'Erik Filipsson';
INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'Filip Gustavsson';

INSERT INTO Events(accountNumber, amount) SELECT accountNumber, 100 FROM Accounts;
INSERT INTO Events(accountNumber, amount) SELECT accountNumber, -200 FROM Accounts WHERE accountNumber = 1;
INSERT INTO Events(accountNumber, amount) SELECT accountNumber, 200 FROM Accounts WHERE accountNumber = 2;
INSERT INTO Events(accountNumber, amount) SELECT accountNumber, -300 FROM Accounts WHERE accountNumber = 3;

INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'Bertil Ceasarsson';
INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'Adam Bertilsson';
INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'Bertil Ceasarsson';
INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'Adam Bertilsson';
INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'Bertil Ceasarsson';
INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'Ceasar Davidsson';
INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'David Eriksson';
INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'Ceasar Davidsson';
INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'David Eriksson';
INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'Erik Filipsson';
INSERT INTO Accounts(customerNumber) SELECT customerNumber FROM Customers WHERE customerName = 'Filip Gustavsson';

select * from Customers;
select * from Accounts;
select * from Events;
