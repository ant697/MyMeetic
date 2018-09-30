<?php
ini_set("display_errors", "on");

class Verification
{
    /**
     * The password Regex: length-min: 8char, maj-min: 1, min-min: 1,
     * num-min: 2
     *
     * @var string
     */
    public $passRegex = "/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/";

    /**
     * Mail Regex check if is conform: [...]@[...].[2/3char]
     *
     * @var string
     */
    public $mailRegex = "/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$/";

    /**
     * Regex for name length-min 3, length-max: 16
     *
     * @var string
     */
    public $nameRegex = "/^[a-zA-Z0-9_]{3,16}$/";

    /**
     * Regex for a country: length-min: 2
     *
     * @var string
     */
    public $countryRegex = "/[a-zA-Z]{2,}/";

    public function createErrorText($message)
    {
        return "<br><div class='justify-content-center row text-center'>
                    <div class='rounded alert alert-danger'>
                        <h2 class='text-danger'>" . $message. "</h2>
                    </div>
                </div>";
    }

    public function createSuccessText($message)
    {
        return "<div class='justify-content-center row text-center'>
                    <div class=' col-4 rounded custom_light btn-light'>
                        <br>
                        <h2 class='text-success'>" . $message . "</h2>
                    </div>
                </div>";
    }

}