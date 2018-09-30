<?php
ini_set("display_errors", "on");

class SearchUser
{

    private $age;

    private $city;

    public function createAgeSelect($name, $age)
    {
        $str = "<select id='$name' class='m-2' name='$name'>";
        for ($i = 18; $i <= 100; $i++) {
            if ($i == $age && $name == "from") {
                $str .= "<option selected value='$i'>$i ans</option>";
            } elseif ($i == $age + 10 && $name == "to") {
                $str .= "<option selected value='$i'>$i ans</option>";
            } else {
                $str .= "<option value='$i'>$i ans</option>";
            }
        }
        $str .= "</select>";
        return $str;
    }
}