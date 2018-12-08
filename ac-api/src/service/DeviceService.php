<?php


namespace service;

use PDO;

/**
 * Class DeviceService
 * @package service
 */
class DeviceService
{


    /**
     * method to get connection to db please change if you want
     * @return PDO
     */
    private function getConnection()
    {

        $host = 'localhost';
        $db = 'cbac';
        $user = 'adnrana';
        $pass = 'password1';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $pdo = new PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
        return $pdo;
    }


    /**
     * get alerts if ppm > 9
     * @param null $rangeArray
     * @param null $filter
     * @return array
     */
    public function getAlerts($rangeArray = null, $filter = null)
    {
        $query = 'select serial_number from measurements where ppm > 9 ';
        $pdo = $this->getConnection();
        $stmt = $pdo->query($query);
        $result = array();

            while ($row = $stmt->fetch()) {
                if (!empty($row)) {
                    $result[] = $row["serial_number"];
                }
            }

        return empty($result)?array():$result;
    }

    /**
     * get devices and masuremnts from database
     * its converted to a format that graphs can understand
     * @param null $deviceId
     * @param null $rangeArray
     * @param null $filter
     * @return array|mixed
     */
    public function getDevicesDb($deviceId = null, $rangeArray = null, $filter = null)
    {
        // first get all the devices
        $pdo = $this->getConnection();

        //range
        if (!empty($rangeArray)) {
            $between = sprintf(" and a.id BETWEEN %s AND %s", $rangeArray[0], $rangeArray[1]);
        }
        //filter
        if (!empty($filter) && is_array($filter)) {

            $needle = $filter[1];

            $like = sprintf("and LOWER(a.serial_number) LIKE LOWER('%%%s%%') ", $needle);


        }


        $query = sprintf('select a.id, a.year, a.serial_number, a.mac, a.owner, a.is_active, a.firmware, 
                  a.created_on, b.humidity, b.ppm, b.temperature, b.health_status, b.created_on, b.device_id, 
                  unix_timestamp(b.created_on) unix_time from devices as a 
                  INNER JOIN measurements as b where a.id=b.device_id %s %s order by b.created_on desc;
', $between, $like);


        $stmt = $pdo->query($query);


        $devices = array();

        while ($row = $stmt->fetch()) {

            $index = $row["id"];

            if (empty($devices[$index])) {
                // if  empty $dev[id] then create array and add  (one entry in thph arrays )
                $devices[$index] = array('id' => $row["id"] . "",
                    'serialNumber' => $row["serial_number"],
                    "macAddress" => $row["mac"],
                    "year" => $row["year"],
                    "isActive" => $row["is_active"],
                    "owner" => $row["owner"],
                    "temperature" => array(
                        array("value" => $row["temperature"], "moment" => $row["unix_time"]),
                    ),
                    "humidity" => array(
                        array("value" => $row["humidity"], "moment" => $row["unix_time"]),
                    ),
                    "ppm" => array(
                        array("value" => $row["ppm"], "moment" => $row["unix_time"]),
                    ),
                    "healthStatus" => array(
                        array("value" => $row["health_status"], "moment" => $row["unix_time"]),
                    )
                );
            } else {
                // if not empty then get $dev[id] and then measurements and add to them
                $device = $devices[$row["id"]];


                array_push($device["temperature"], array(
                    array("value" => $row["temperature"], "moment" => $row["unix_time"]),
                ));


                array_push($device["humidity"], array(
                    array("value" => $row["humidity"], "moment" => $row["unix_time"]),
                ));
                array_push($device["ppm"], array(
                    array("value" => $row["ppm"], "moment" => $row["unix_time"]),
                ));
                array_push($device["healthStatus"], array(
                    array("value" => $row["healthStatus"], "moment" => $row["unix_time"]),
                ));

                $devices[$row["id"]] = $device;
            }


        }

        $result = array();

        if (!empty($deviceId) || null != $deviceId) {

            return $devices[$deviceId];
        }

        foreach ($devices as $device) {

            $result[] = $device;

        }


        return $result;

    }

    /**
     * @param $record
     * @return string
     */
    public function register($record)
    {
        if (empty($record)) {
            return "EMPTY Request";
        }
        $pdo = $this->getConnection();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $pdo->beginTransaction();
            $statement = sprintf('insert into  devices (year, serial_number, mac, owner, firmware  )  ' .
                'VALUES ("%s","%s", "%s", "%s", "%s")',
                $record["year"], $record["serialNumber"], $record["macAddress"], $record["owner"], $record["firmware"]);
            $pdo->prepare($statement)
                ->execute();


            $pdo->commit();

        } catch (PDOException $e) {


            throw $e;


        }
        return "success";

    }

    /**
     * @param $record
     * @return string
     */
    public function addMeasurements($record)
    {
        if (empty($record)) {
            return "EMPTY Request";
        }
        $pdo = $this->getConnection();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            // its a bulk insert operation
            // we will create bulk inserts and will push all of them in one transaction

            $multiple = false;

            if (is_array($record) && is_array($record[0])) {
                if (sizeof($record) > 500) {
                    return "cannot process more than 500 measurements.";
                }
                $multiple = true;
            }


            $statments = array();
            $format = "insert into  measurements (created_on, temperature, humidity, serial_number, health_status, ppm, device_id   ) 
	select '%s','%s','%s','%s','%s','%s',id from devices where serial_number = '%s'";

            if ($multiple) {

                foreach ($record as $row) {

                    if (strlen($row["healthStatus"]) > 150) {
                        return "health status cannot exceed size 150 ";

                    }
                    $ts = date("Y-m-d H:i:s");
                    if (!empty($row["createdOn"])) {
                        $ts = date("Y-m-d H:m:s", $row["createdOn"]);

                    }
                    $statments[] = $pdo->prepare(sprintf($format,
                        $ts,
                        $row["temperature"],
                        $row["humidity"],
                        $row["serialNumber"],
                        $row["healthStatus"],
                        $row["ppm"],
                        $row["serialNumber"]));
                }

            } else {

                if (strlen($record["healthStatus"]) > 150) {
                    return "health status cannot excedd size 150 ";

                }
                $ts = date("Y-m-d H:i:s");
                if (!empty($record["createdOn"])) {
                    $ts = date("Y-m-d H:m:s", $record["createdOn"]);

                }
                $statments[] = $pdo->prepare(sprintf($format,
                    $ts,
                    $record["temperature"],
                    $record["humidity"],
                    $record["serialNumber"],
                    $record["healthStatus"],
                    $record["ppm"],
                    $record["serialNumber"]));
            }

            $pdo->beginTransaction();
            foreach ($statments as $statment) {
                $statment->execute();
            }
            $pdo->commit();

        } catch (PDOException $e) {


            throw $e;


        }
        return "success";

    }

    /**
     * @param null $index
     * @param null $rangeArray
     * @param null $filter
     * @return array|mixed
     */
    public
    function getDevicesMock($index = null, $rangeArray = null, $filter = null)
    {
        $targets = array();
        $j = 201;
        $i = 1;
        if (!empty($rangeArray)) {

            $i = $rangeArray[0] + 1;
            $j = $rangeArray[1];
        }

        for (; $i <= $j; $i++) {


            if (!empty($filter) && is_array($filter)) {

                $needle = $filter[1];

                $test1 = stripos("SN" . $i, trim($needle));
                $test2 = strcasecmp("SN" . $i, trim($needle));

                if ($test1 === FALSE && $test2 !== 0) {
                    continue;
                }


            }

            $targets[] = array('id' => $i . "", 'serialNumber' => "SN" . $i, "macAddress" => "1111111" . $i, "year" => "2018",
                "isActive" => true, "owner" => "Hiba",
                "temperature" => array(
                    array("value" => "1", "moment" => "1503616297689"),
                    array("value" => "2", "moment" => "1503616298000"),
                    array("value" => "6", "moment" => "1503616297689")
                ),
                "humidity" => array(
                    array("value" => "8", "moment" => "1503615297689"),
                    array("value" => "3", "moment" => "1503614298000"),
                    array("value" => "12", "moment" => "1503667297689")
                ),
                "ppm" => array(
                    array("value" => "4", "moment" => "1503615297689"),
                    array("value" => "3", "moment" => "1503614298000"),
                    array("value" => "3", "moment" => "1503614297689")
                ),
                "healthStatus" => array(
                    array("value" => "4", "moment" => "need service"),
                    array("value" => "3", "moment" => "need service"),
                    array("value" => "3", "moment" => "need service")
                )
            );


        }
        if (!empty($index) || null != $index) {
            return $targets[$index - 1];
        }
        return $targets;
    }

    /**
     * @param $serial
     * @return array|string
     */
    public
    function getMeasurements($serial)
    {
        if(empty($serial)){
            return "empty serial";
        }
        $like = sprintf(" where LOWER(serial_number) LIKE LOWER('%%%s%%') ", $serial);
        $query = 'select * from measurements '.$like;
        $pdo = $this->getConnection();
        $stmt = $pdo->query($query);
        $result = array();

        while ($row = $stmt->fetch()) {
            if (!empty($row)) {
                $result[] = $row;
            }
        }

        return empty($result)?array():$result;
    }

    /**
     * DeviceService constructor.
     */
    public
    function __construct()
    {

    }

    /**
     * @param $userId
     * @return bool
     */
    private
    function validateUser($userId)
    {
        return true;
    }

}