var app = angular.module('app', ['ui.router']);


app.config(function($stateProvider, $urlRouterProvider) {

  $urlRouterProvider.otherwise("/home");

    $stateProvider
    .state('home', {
      url: "/home",
      templateUrl: "templates/home.html",
      controller: 'HomeCtrl'
    })

    .state('register', {
      url: "register",
      params : {user:null},
      controller: 'RegCtrl',
      templateUrl: "templates/register.html"
    })


   /* .state('customerHome', {
      url: "/customer/home",
      params:{user:null},
      controller: 'CustHomeCtrl',
      templateUrl: "templates/custhome.html"
    })*/
    .state('login', {
      url: "/login",
      params:{role:null},
      controller: 'LoginCtrl',
      templateUrl: "templates/login.html"
    });
  });


app.directive('headerTpl', function () {
    return {
        templateUrl: 'templates/header.html'
    }
});