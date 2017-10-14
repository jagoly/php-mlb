
drop database if exists MyLifeBalance;
create database MyLifeBalance;

use MyLifeBalance;

/********************
* Customers         *
* Employees         *
* Shifts            *
* Salaries          *
* Appointments      *
* Products          *
* Sales             *
* Purchases         *
********************/

/*============================================================================*/

CREATE TABLE `Customers`
(
  `CustomerID` int(8) NOT NULL AUTO_INCREMENT,
  `LastName` varchar(30) NOT NULL,
  `FirstName` varchar(30) NOT NULL,
  `Address` varchar(200) NOT NULL,
  PRIMARY KEY (`CustomerID`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Employees`
(
  `EmployeeID` int(8) NOT NULL AUTO_INCREMENT,
  `LastName` varchar(30) NOT NULL,
  `FirstName` varchar(30) NOT NULL,
  `Address` varchar(200) NOT NULL,
  PRIMARY KEY (`EmployeeID`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Shifts`
(
  `EmployeeID` int(8) NOT NULL,
  `ShiftDate` date NOT NULL,
  `NumHours` decimal(2,1) NOT NULL,
  PRIMARY KEY (`EmployeeID`,`ShiftDate`),
  CONSTRAINT `Shifts_Employees_FK` FOREIGN KEY (`EmployeeID`) REFERENCES `Employees` (`EmployeeID`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Salaries`
(
  `EmployeeID` int(8) NOT NULL,
  `SalaryDate` date NOT NULL,
  `TotalHours` decimal(3,1) NOT NULL,
  `TotalPay` decimal(5,2) NOT NULL,
  PRIMARY KEY (`EmployeeID`,`SalaryDate`),
  CONSTRAINT `Salaries_Employees_FK` FOREIGN KEY (`EmployeeID`) REFERENCES `Employees` (`EmployeeID`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Appointments`
(
  `CustomerID` int(8) NOT NULL,
  `EmployeeID` int(8) NOT NULL,
  `AppointmentDate` date NOT NULL,
  `Outcome` longtext NOT NULL,
  PRIMARY KEY (`EmployeeID`,`CustomerID`,`AppointmentDate`),
  CONSTRAINT `Appointments_Customers_FK` FOREIGN KEY (`CustomerID`) REFERENCES `Customers` (`CustomerID`),
  CONSTRAINT `Appointments_Employees_FK` FOREIGN KEY (`EmployeeID`) REFERENCES `Employees` (`EmployeeID`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Products`
(
  `ProductID` int(8) NOT NULL AUTO_INCREMENT,
  `VendorCode` varchar(80) NOT NULL,
  `ShortName` varchar(80) NOT NULL,
  `StandardPrice` decimal(5,2) NOT NULL,
  PRIMARY KEY (`ProductID`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Sales`
(
  `SaleID` int(8) NOT NULL AUTO_INCREMENT,
  `CustomerID` int(8) NOT NULL,
  `ProductID` int(8) NOT NULL,
  `Quantity` decimal(4,0) NOT NULL,
  `TotalPrice` decimal(5,2) NOT NULL,
  `SaleDate` date NOT NULL,
  PRIMARY KEY (`SaleID`),
  CONSTRAINT `Sales_Customers_FK` FOREIGN KEY (`CustomerID`) REFERENCES `Customers` (`CustomerID`),
  CONSTRAINT `Sales_Products_FK` FOREIGN KEY (`ProductID`) REFERENCES `Products` (`ProductID`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Purchases`
(
  `PurchaseID` int(8) NOT NULL AUTO_INCREMENT,
  `ProductID` int(8) NOT NULL,
  `Quantity` decimal(4,0) NOT NULL,
  `TotalPrice` decimal(5,2) NOT NULL,
  `PurchaseDate` date NOT NULL,
  PRIMARY KEY (`PurchaseID`),
  CONSTRAINT `Purchases_Products_FK` FOREIGN KEY (`ProductID`) REFERENCES `Products` (`ProductID`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Workshops`
(
  `WorkshopID` int(8) NOT NULL AUTO_INCREMENT,
  `ProductID` int(8) NOT NULL,
  `WorkshopDate` date NOT NULL,
  PRIMARY KEY (`WorkshopID`),
  CONSTRAINT `Workshops_Products_FK` FOREIGN KEY (`ProductID`) REFERENCES `Products` (`ProductID`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Enrollments`
(
  `CustomerID` int(8) NOT NULL,
  `WorkshopID` int(8) NOT NULL,
  `Grade` varchar(30),
  PRIMARY KEY (`CustomerID`, `WorkshopID`),
  CONSTRAINT `Grades_Customers_FK` FOREIGN KEY (`CustomerID`) REFERENCES `Customers` (`CustomerID`),
  CONSTRAINT `Grades_Workshops_FK` FOREIGN KEY (`WorkshopID`) REFERENCES `Workshops` (`WorkshopID`)
)
ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*============================================================================*/

insert into Customers ( CustomerID, LastName, FirstName, Address ) values ( default, 'Burgle', 'Gloria', 'Eden Valley' );
insert into Customers ( CustomerID, LastName, FirstName, Address ) values ( default, 'Stussy', 'Ennis', 'Eden Valley' );
insert into Customers ( CustomerID, LastName, FirstName, Address ) values ( default, 'Stussy', 'Emmit', 'Eden Prairie' );
insert into Customers ( CustomerID, LastName, FirstName, Address ) values ( default, 'Stussy', 'Raymond', 'Somewhere' );
insert into Customers ( CustomerID, LastName, FirstName, Address ) values ( default, 'Swango', 'Nikki', 'Somewhere' );

insert into Employees ( EmployeeID, LastName, FirstName, Address ) values ( default, 'Varga', 'V.M.', 'The World' );
insert into Employees ( EmployeeID, LastName, FirstName, Address ) values ( default, 'Gurka', 'Yuri', 'East Germany' );

insert into Shifts ( EmployeeID, ShiftDate, NumHours ) values ( 1, '2011-04-10', 6.0 );
insert into Shifts ( EmployeeID, ShiftDate, NumHours ) values ( 1, '2011-04-11', 6.0 );
insert into Shifts ( EmployeeID, ShiftDate, NumHours ) values ( 1, '2011-04-12', 2.0 );
insert into Shifts ( EmployeeID, ShiftDate, NumHours ) values ( 2, '2011-04-12', 8.0 );
insert into Shifts ( EmployeeID, ShiftDate, NumHours ) values ( 1, '2011-04-13', 2.0 );
insert into Shifts ( EmployeeID, ShiftDate, NumHours ) values ( 2, '2011-04-13', 8.0 );
insert into Shifts ( EmployeeID, ShiftDate, NumHours ) values ( 1, '2011-04-14', 2.0 );
insert into Shifts ( EmployeeID, ShiftDate, NumHours ) values ( 2, '2011-04-14', 8.0 );
insert into Shifts ( EmployeeID, ShiftDate, NumHours ) values ( 1, '2011-05-07', 9.0 );
insert into Shifts ( EmployeeID, ShiftDate, NumHours ) values ( 1, '2011-05-08', 9.0 );

insert into Appointments ( CustomerID, EmployeeID, AppointmentDate, Outcome ) values ( 2, 1, '2011-04-12', 'Nothing of interest.' );
insert into Appointments ( CustomerID, EmployeeID, AppointmentDate, Outcome ) values ( 3, 1, '2011-04-12', 'Lots of interesting things.' );

insert into Products ( ProductID, VendorCode, ShortName, StandardPrice ) values ( default, 'MLB-FOO-1', 'Aardvarks and You', 19.95 );
insert into Products ( ProductID, VendorCode, ShortName, StandardPrice ) values ( default, '0-399-22690-7', 'The Very Hungry Caterlillar', 19.95 );

insert into Workshops ( WorkshopID, ProductID, WorkshopDate ) values ( default, 1, '2011-06-25' );

insert into Enrollments ( CustomerID, WorkshopID, Grade ) values ( 1, 1, null );
