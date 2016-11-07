<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Driver based Cart</title>

    <!-- Bootstrap core CSS -->
    <link href="/assets/css/bootstrap.css" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/assets/css/dashboard.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="/">Driver based Cart</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <form class="navbar-form navbar-right" method="post">
                {if isset($user)}
                    <span class="text-primary">{$user}</span>&nbsp;&nbsp;&nbsp;
                    <input name="logout" type="submit" class="btn btn-danger" value="logout">
                {else}
                    <input name="username" type="text" class="form-control" placeholder="Name">
                    <input name="password" type="password" class="form-control" placeholder="Password">
                    <input name="login" type="submit" class="btn btn-primary" value="login">
                {/if}
            </form>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <li class="active"><a href="#">Overview <span class="sr-only">(current)</span></a></li>
                <li><a href="#">Reports</a></li>
                <li><a href="#">Analytics</a></li>
                <li><a href="#">Export</a></li>
            </ul>

        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            {if $cart->get_items()|@count gt 0}
            <h2 class="sub-header">Cart Items</h2>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Title</th>
                        <th>Quantity</th>
                        <th>Price per peice without VAT</th>
                        <th>Price per peice with VAT</th>
                        <th>Vat %</th>
                        <th>Vat</th>
                        <th>Price without VAT</th>
                        <th>Price with VAT</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach from=$cart->get_items() item=item}
                        <tr>
                            <td>{$item->get_code()}</td>
                            <td>{$item->get_title()}</td>
                            <td>{$item->get_quantity()}</td>
                            <td>{$item->get_price()|price}</td>
                            <td>{$item->get_price_tax()|price}</td>
                            <td>{$item->get_tax()} %</td>
                            <td>{$item->get_total_tax()|price}</td>
                            <td>{$item->get_total_price()|price}</td>
                            <td>{$item->get_total_price_tax()|price}</td>
                            <td>
                                <form method="post">
                                    <input name="delete_item" type="hidden" value="{$item->get_code()}">
                                    <input type="submit" class="btn btn-danger" value="x">
                                </form>
                            </td>
                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            </div>
            <div class="col-sm-6 col-md-8"></div>
            <div class="col-sm-6 col-md-4">
                <h3>Summary</h3>
                <table class="table">
                    <tbody>
                    <tr>
                        <td>Total VAT</td>
                        <td>{$cart->get_tax()|price}</td>
                    </tr>
                    <tr>
                        <td>Total price</td>
                        <td>{$cart->get_price()|price}</td>
                    </tr>
                    <tr>
                        <td>Total price with VAT</td>
                        <td>{$cart->get_price_tax()|price}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            {else}
                <div class="alert alert-info" role="alert">Your cart is empty!</div>
            {/if}
            <div class="col-sm-1 col-md-1">
                <form method="post">
                    <input name="add_item" type="hidden" value="1">
                    <input type="submit" class="btn btn-info" value="Add item 1">
                </form>
            </div>
            <div class="col-sm-1 col-md-1">
                <form method="post">
                    <input name="add_item" type="hidden" value="2">
                    <input type="submit" class="btn btn-info" value="Add item 2">
                </form>
            </div>
            <div class="col-sm-1 col-md-1">
                <form method="post">
                    <input name="clear" type="hidden" value="1">
                    <input type="submit" class="btn btn-danger" value="Clear cart">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="../../dist/js/bootstrap.min.js"></script>
<!-- Just to make our placeholder images work. Don't actually copy the next line! -->
<script src="../../assets/js/vendor/holder.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
