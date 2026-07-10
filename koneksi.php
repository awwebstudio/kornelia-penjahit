<?php

$koneksi = mysqli_connect("localhost", "root", "", "kornelia");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}