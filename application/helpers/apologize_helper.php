<?php
    function apologize($message)
    {
        $this->view('templates/header');
        $this->view('apologize', $message);
        $this->view('templates/footer');
        exit;
    }

?>
