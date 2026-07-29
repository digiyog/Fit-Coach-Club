@extends('errors.errors-layout')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __(__('error_messages.module_access_denied') ?: 'Forbidden'))
