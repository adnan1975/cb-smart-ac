DROP DATABASE cbac;
CREATE DATABASE cbac;

USE cbac; 

CREATE TABLE devices
(
  id              INT unsigned NOT NULL AUTO_INCREMENT, # Unique ID for the record
  year            VARCHAR(150) NOT NULL,                
  serial_number           VARCHAR(150) NOT NULL UNIQUE,                
  mac            VARCHAR(150) NOT NULL,               
  owner           VARCHAR(150) ,    
  is_active  INT DEFAULT 1, 
  firmware   VARCHAR(150) , 
  created_on 	TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

  PRIMARY KEY     (id)                                  # Make the id the primary key

);


CREATE TABLE measurements
(
  id              INT unsigned NOT NULL AUTO_INCREMENT, 
  serial_number           VARCHAR(150) NOT NULL ,       
	humidity            VARCHAR(150) NOT NULL,                
  ppm           VARCHAR(150) ,
  temperature              VARCHAR(150),  
  health_status     VARCHAR(150) , 
  created_on 	TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  device_id INT unsigned,
  PRIMARY KEY     (id),                                  # Make the id the primary key
  INDEX dev_ind (device_id),
  FOREIGN KEY (device_id)
  REFERENCES devices(id)
     ON DELETE CASCADE
  
);


GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'password1' WITH GRANT OPTION;


create user 'adnrana'@'localhost' identified by 'password1'; 
GRANT ALL PRIVILEGES ON *.* TO 'adnrana'@'localhost' 
IDENTIFIED BY 'password1' 
WITH GRANT OPTION;

GRANT ALL PRIVILEGES ON *.* TO 'username'@'localhost' IDENTIFIED BY 'password';




pasword for root

7b6009731572a5ff28f888b4e25f60fa6e7f3b293c270814




{"serialNumber":"SN1","macAddress":"11111111","year":"2018","owner":"scott", "firmware":"2.1.5"}

{"serialNumber":"SN1","humidity":"1","ppm":"2","health_status":"need_service"}

(field1, field3) VALUES (5, 10);

insert into  devices (year, serial_number, mac, owner, firmware  ) VALUES ("2018","SN1", "2312", "12312", "1.1");
	insert into  devices (year, serial_number, mac, owner, firmware  ) VALUES ("2018","SN2", "MAC2", "12312", "1.1");

insert into  devices (year, serial_number, mac, owner, firmware  ) VALUES ("2018","SN3", "2312", "MAC3", "1231");

    , 
  
  PRIMARY KEY     (id)                                  # Make the id the primary key

);


insert into  measurements (humidity, serial_number, health_status, ppm  ) 
	select '1',id,'need_service',3 from devices where serial_number = 'SN1';

select a.*,b.* from devices as a INNER JOIN measurements as b where a.id=b.device_id order by b.created_on desc;

SELECT Orders.OrderID, Customers.CustomerName, Orders.OrderDate
FROM Orders
INNER JOIN Customers ON Orders.CustomerID=Customers.CustomerID; 

[{"serialNumber":"SN3","humidity":"1","ppm":"10","healthStatus":"need_service","createdOn":"1544235156","temperature":"12"},{"serialNumber":"SN3","humidity":"1","ppm":"2","healthStatus":"need_service","createdOn":"1544235156","temperature":"12"}]

