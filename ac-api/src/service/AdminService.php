<?php

namespace service;


class AdminService
{


    /**
     * @param null $index
     * @param null $rangeArray
     * @param null $filter
     * @return array|mixed
     */
    public
    function getAdminsMock($index = null, $rangeArray = null, $filter = null)
    {
        $faker = \Faker\Factory::create();
        $faker->seed(7668);

        $targets = array();
        $j = 10;
        $i = 1;
        if (!empty($rangeArray)) {

            $i = $rangeArray[0] + 1;
            $j = $rangeArray[1];
        }

        for (; $i <= $j; $i++) {


            $name = $faker->name;

            if (!empty($filter) && is_array($filter)) {

                $needle = $filter[1];


                $test1 = stristr("$name", trim($needle));
                $test2 = strcasecmp("$name", trim($needle));

                if ($test1 === FALSE && $test2 !== 0) {

                    $faker->userName;
                    $faker->companyEmail;

                    continue;
                }


            }

            $targets[] = array('id' => $i . "",
                'name' => $name,
                'username' => $faker->userName,
                "email" => $faker->companyEmail
            );


        }
        if (!empty($index) || null != $index) {
            return $targets[$index - 1];
        }
        return $targets;
    }

}