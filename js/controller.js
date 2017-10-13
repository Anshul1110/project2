    function twoDigits(d) {
        if(0 <= d && d < 10) return "0" + d.toString();
        if(-10 < d && d < 0) return "-0" + (-1*d).toString();
        return d.toString();
    }
    //do min aa raha hun...ohk
    Date.prototype.toMysqlFormat = function(h, m, s) {
        return this.getUTCFullYear() + "-" + twoDigits(1 + this.getUTCMonth()) + "-" + twoDigits(1 + this.getUTCDate()) + " " + twoDigits((h || h===0)?h:this.getUTCHours()) + ":" + twoDigits((m || m===0)?m:this.getUTCMinutes()) + ":" + twoDigits((s || s===0)?s:this.getUTCSeconds());
    };

    app.controller('HomeCtrl', function($scope, $state,$rootScope,$http, $stateParams){

        $scope.userDetails = {
               e: '' 
            }
        
        $scope.login = function(){
    		$state.go('login')
    	}
    	$scope.register = function(){
    		$state.go('register', {ref:$stateParams.ref})
    	}
    	$rootScope.$on('event:social-sign-in-success', function(event, userDetails){
            /*$scope ka matlab kya hai? 1 primary variable usise hum saare define krte h angular me. most imp is,
            ye file ek .js file hai to normal js ki cheeze chalegi yaha
            toh matlab ye ki agar front end par value dikhana hai / kisi 2-3 function ke beech value share karni hai (like javascript global variable - pata hai?..haan function k bahar likhte h ) sirf unhi cases me $scope.a/b/c karna hai..ok boss yaha par nahi karna hai aisa kuch samjha??
            abe iss function me ye dondo nahi karna hai na? haan isliye yaha no $scope.
            */
    		//whats the diff?..upar wala to chalega hi nh 
            console.log(userDetails);
            console.log($stateParams)
    		$http({
                url: "backend/socialLogin.php",
                method: "POST",
                data: userDetails, //$scope.userDetails not defeind
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(data, status, headers, config) {
    			console.log("User from back end");		
                console.log(data)
                 $state.go('loginHome', {user:data.user});
            }).error(function(data, status, headers, config) {
                $state.go('home');
            });
    	})
    })
    app.controller('LoginCtrl', function($scope, $state, $stateParams, $http,Socialshare){
    	$scope.user = {
    		u: 'aman',
    		p: 'aman'
    	}
    	$scope.register = function(){
    		$state.go('register')
    	}
    	$scope.login = function(){
    		console.log("User in front end");		
    		console.log($scope.user);		
        	$http({
                url: "backend/login.php",
                method: "POST",
                data: $scope.user,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(data, status, headers, config) {
    			console.log("User from back end");		
                console.log(data)
                 $state.go('loginHome', {user:data.user});
            }).error(function(data, status, headers, config) {
                $state.go('home');
            });
        }
        
    })
    .controller('RegCtrl', function($scope, $state, $stateParams,$http){
        console.log($stateParams)
    	$scope.user = {
    		u: '',
    		p: '',
    		e: '',
            ref : $stateParams.ref
    	   }
        $scope.disableRef = false;
        if($scope.user.ref.length > 0){
            $scope.disableRef = true;
            }
    	$scope.register = function(){
    		console.log("User in front end");		
    		console.log($scope.user);
               if($scope.user.p == $scope.user.c){
            $http({
                url: "backend/register.php",
                method: "POST",
                data: $scope.user,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function(data, status, headers, config) {
                console.log("User from back end");      
                console.log(data);
               $state.go('home');
            }).error(function(data, status, headers, config) {
                $state.go('home');
            });
            }else{
                alert("mismatch");
            }		

            }
        })
    .controller('LoginHomeCtrl', function($scope, $state, $stateParams,$location,Socialshare){
    	$scope.user = $stateParams.user;
        console.log("loginHome"); 
    	console.log($stateParams); 
    	hometl = new TimelineMax();
        TweenMax.set("#header", {css:{y:"-150%",opacity:0}});

    	TweenMax.set("#column1", {css:{x:"-150%",opacity:0}});
    	TweenMax.set("#button a", {css:{y:"-150%",opacity:0}});
    	hometl
    	.add("label1")
    	.to( "#header", 1, {  y:"0%", opacity:1, ease:Back.easeOut 
    	}, "label1")
    	.add("label2", "label1+=3")
    	.to( "#column1", 1, {  x:"0%", opacity: 1, ease:Back.easeOut }, "label2")
    	.staggerTo("#button a", 1, {y:"0%", opacity:1, ease:Back.easeOut}, 0.2, "label2") 
    	;
        function getRandomIntInclusive(min,max){
            min = Math.ceil(min);
            max = Math.floor(max);
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }
        x = getRandomIntInclusive(0,255);
        y = getRandomIntInclusive(0,255);
        z = getRandomIntInclusive(0,255);
        var str = "rgb(" + x +", "+ y +", "+ z +")";
        TweenMax.set("#backg", {css:{backgroundColor : str}});
        
        $scope.shareLink = function(type){
            $scope.user = $stateParams.user;
            var link = $location.$$absUrl;
            link = link.split("/");
            delete link[link.length - 1];
            link = link.join("/") + "home/" + $scope.user['u_ref'];
            switch(type){

                case 'fb':
                console.log("fb")
                    Socialshare.share({
                    'provider': 'facebook',
                    'attrs': {
                    'socialshareUrl': link,
                    'socialshareText': "Hi, register on this website with my referral code!"
                    }
                });
                case 'google':
                    console.log("google");
                    Socialshare.share({
                    'provider': 'google',
                    'attrs': {
                    'socialshareUrl': link,
                    'socialshareText': "Hi, register on this website with my referral code!"
                    }
                });
                    break;
                case 'wa':
                console.log("wa");
                    if(window.innerWidth <= 480){ 
                    window.open('whatsapp://send?text=Hi! Register on this website using my referral link : ' + link, '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=500,width=700,height=400');
                    }else{
                    window.open('https://web.whatsapp.com/send?text=Hi! Register on this website using my referral link : ' + link, '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=500,width=700,height=400');
                    }
                    break;
            }
        }

        })

