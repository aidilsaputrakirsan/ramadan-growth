@extends('errors.layout')

@section('title', 'Terlalu Banyak Permintaan')
@section('code', '429')
@section('message', 'Sabar Ya!')
@section('description', 'Kamu mengirimkan terlalu banyak permintaan dalam waktu singkat. Silakan tunggu beberapa saat sebelum mencoba kembali.')