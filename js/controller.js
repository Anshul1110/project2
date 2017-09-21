function twoDigits(d) {
    if(0 <= d && d < 10) return "0" + d.toString();
    if(-10 < d && d < 0) return "-0" + (-1*d).toString();
    return d.toString();
}

Date.prototype.toMysqlFormat = function(h, m, s) {
    return this.getUTCFullYear() + "-" + twoDigits(1 + this.getUTCMonth()) + "-" + twoDigits(1 + this.getUTCDate()) + " " + twoDigits((h || h===0)?h:this.getUTCHours()) + ":" + twoDigits((m || m===0)?m:this.getUTCMinutes()) + ":" + twoDigits((s || s===0)?s:this.getUTCSeconds());
};

app.controller('HomeCtrl', function($scope, $state){
	$scope.doLogin = function(role){
		$state.go('login', {role: role})
	}
})
app.controller('LoginCtrl', function($scope, $state, $stateParams){
	if($stateParams.role==null){
		$state.go('home');
	}
	$scope.user = {
		u: '',
		p: '',
		r: $stateParams.role
	}
	$scope.login = function(){
		console.log($scope.user);
		switch($scope.user.r){
			case 'Online Entrepreneur' : $state.go('login'); break;
			/*case 'Admin' : $state.go('adminLogin'); break;
			case 'Customer' : $state.go('customerLogin'); break;
			case 'Merchant' : $state.go('merchantLogin'); break;*/	
		}	
	}
	$scope.register = function(){
		console.log($scope.user);
		switch($scope.user.r){
			case 'Register' : $state.go('register'); break;
		}	
	}
})
app.controller('RegCtrl', function($scope, $state, $stateParams){
	if($stateParams.role==null){
		$state.go('home');
	}
	$scope.user = {
		u: '',
		p: '',
		e: '',
		r: $stateParams.role
	}
	$scope.login = function(){
		console.log($scope.user)
		switch($scope.user.r){
			case 'Online Entrepreneur' : $state.go('login'); break;
			/*case 'Admin' : $state.go('adminLogin'); break;
			case 'Customer' : $state.go('customerLogin'); break;
			case 'Merchant' : $state.go('merchantLogin'); break;*/	
		}	
	}
	$scope.register = function(){
		switch($scope.user.r){
			/*case 'Online Entrepreneur' : $state.go('agentRegister'); break;*/
			case 'Register' : $state.go('register'); break;
			/*case 'Merchant' : $state.go('merchantRegister'); break;	*/
		}	
	}
})
/*.controller('RegCtrl', function($scope, $state, $stateParams, $location, $http, Socialshare){
	if($stateParams.user==null){
		$state.go('home');
	}else{
		$scope.user = $stateParams.user;
		$scope.user.r = 'Customer';		
	}*/
	/*$scope.getReferrals = function(){
    	$http({
            url: "backend/getReferrals.php",
            method: "POST",
            data: $scope.user,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data, status, headers, config) {
            $scope.user.referrals = data.referrals;
        }).error(function(data, status, headers, config) {
            $state.go('home');
        });
    }*/

   /* $scope.shareLink = function(type){
        var link = $location.$$absUrl;
        link = link.split("/");
        delete link[link.length - 1];
        link = link.join("/") + "register/" + $scope.user['c_ref'];
        switch(type){
            case 'url': 
                prompt("Here is the link you can share:", link);
                break;
            case 'fb':
                Socialshare.share({
                  'provider': 'facebook',
                  'attrs': {
                    'socialshareUrl': link,
                    'socialshareText': "Hi, register on this website with my referral code!"
                  }
                });
                break;
            case 'wa':
                if(window.innerWidth <= 480){ 
                    window.open('whatsapp://send?text=Hi! Register on this website using my referral link : ' + link, '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=500,width=700,height=400');
                }else{
                    window.open('https://web.whatsapp.com/send?text=Hi! Register on this website using my referral link : ' + link, '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=500,width=700,height=400');
                }
                break;
        }
    }*/

  /*  $scope.getReferrals();
})*/