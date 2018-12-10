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



            $targets[] = array('id' => $i . "", 'name' => $faker->name,
                'username' =>$faker->userName,
                "email" => $faker->companyEmail
            );


        }
        if (!empty($index) || null != $index) {
            return $targets[$index - 1];
        }
        return $targets;
    }

}