

var app = angular.module('app', ['ui.router', 'socialLogin', '720kb.socialshare']);


app.config(function($stateProvider, $urlRouterProvider) {

  $urlRouterProvider.otherwise("/home");

    $stateProvider
    .state('home', {
      url: "/home",
      templateUrl: "templates/home.html",
      controller: 'HomeCtrl'
    })

    .state('register', {
      url: "/register",
      params : {user:null},
      controller: 'RegCtrl',
      templateUrl: "templates/register.html"
    })
    .state('login', {
      url: "/login",
      params:{role:null},
      controller: 'LoginCtrl',
      templateUrl: "templates/login.html"
    })
    .state('loginHome', {
      url: "/loginHome",
      params:{user:null},
      controller: 'LoginHomeCtrl',
      templateUrl: "templates/loginHome.html"
    });
  });


app.directive('headerTpl', function () {
    return {
        templateUrl: 'templates/header.html'
    }
});
app.config(function(socialProvider){
    socialProvider.setGoogleKey("997683601649-q3mmhd6anbel2ht6416b89nmu644uvkl.apps.googleusercontent.com");
    socialProvider.setFbKey({appId: "843629469133815", apiVersion: "v2.10"});
});