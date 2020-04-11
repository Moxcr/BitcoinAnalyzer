<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=Edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Bitcoin Analyzer</title>

  <link type="text/css" rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
</head>

<body>

<div class="container">
    <div class="mt-5"><canvas id="bitcoin_chart"></canvas></div>
    <div class="mt-1">
        <div class="row w-75 m-auto">
            <div class="col-9">
                <div class="row">
                    <div class="col-6">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">Start Date</span></div>
                            <input class="form-control" type="text" id="start_date">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">End Date</span></div>
                            <input class="form-control" type="text" id="end_date">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <button onclick="updateChart()" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
    <div class="mt-2">
        <div class="text-center alert alert-danger" id="error_box" style="display: none;"></div>
    </div>
</div>

<script>
var chartOptions = {
    legend: {
        labels: {
            fontColor: 'black'
        }
    },
    tooltips: {
            mode: 'index',
            intersect: false,
    },
    elements: {
        line: {
            fill: false,
            backgroundColor: 'white',
            borderColor: 'lightblue'
        },
        point: {
            backgroundColor: 'lightblue',
            hoverBackgroundColor: 'skyblue',
            radius: 10,
            hoverRadius: 20
        }
    }
};

$(document).ready(function() {
    var seven_days_ago = new Date(Date.now() - (7 * 24 * 60 * 60 * 1000));
    var start_date = seven_days_ago.toISOString().substring(0, 10);
    $('#start_date').val(start_date);

    var end_date = new Date().toISOString().substring(0, 10);
    $('#end_date').val(end_date);

    $.post('/graph_data', {start: start_date, end: end_date}, function(bitcoin_data) {

        var data = {
            labels: bitcoin_data.map(record => new Date(record.record_date).toDateString()).reverse(),
            datasets: [{
                label: "Bitcoin Open Value",
                data: bitcoin_data.map(record => record.open_value).reverse()
            }]
        };

        window.bitcoinChart = new Chart('bitcoin_chart', {
            type: 'line',
            data: data,
            options: chartOptions
        });
    }, 'json');
});

function updateChart() {
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();

    var formatCheck = /\d{4}-\d{2}-\d{2}/;
    if (!formatCheck.test(start_date) || !formatCheck.test(end_date)) {
        displayError("Invalid date format. Please ensure Start Date and End Date follow the YYYY-MM-DD date format.");
        return;
    }

    if (new Date(end_date).getTime() < new Date(start_date).getTime()) {
        displayError("Start Date must come before End Date.");
        return;
    }

    if (new Date(end_date).getTime() > new Date().getTime()) {
        end_date = new Date().toISOString().substring(0, 10);
        $('#end_date').val(end_date);
    }

    $.post('/graph_data', {start: start_date, end: end_date}, function(bitcoin_data) {

        var data = {
            labels: bitcoin_data.map(record => new Date(record.record_date).toDateString()).reverse(),
            datasets: [{
                label: "Bitcoin Open Value",
                data: bitcoin_data.map(record => record.open_value).reverse()
            }]
        };

        bitcoinChart.data = data;
        bitcoinChart.update();

        hideErrors();
    }, 'json');
}

function displayError(text) {
    $('#error_box').html(text).show();
}

function hideErrors() {
    $('#error_box').hide();
}
</script>

</body>
</html>