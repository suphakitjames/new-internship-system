<?php
// src/Auth/logout.php

session_destroy();
header('Location: index.php');
exit;
