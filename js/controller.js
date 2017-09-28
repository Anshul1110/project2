function twoDigits(d) {
    if(0 <= d && d < 10) return "0" + d.toString();
    if(-10 < d && d < 0) return "-0" + (-1*d).toString();
    return d.toString();
}
//do min aa raha hun...ohk
Date.prototype.toMysqlFormat = function(h, m, s) {
    return this.getUTCFullYear() + "-" + twoDigits(1 + this.getUTCMonth()) + "-" + twoDigits(1 + this.getUTCDate()) + " " + twoDigits((h || h===0)?h:this.getUTCHours()) + ":" + twoDigits((m || m===0)?m:this.getUTCMinutes()) + ":" + twoDigits((s || s===0)?s:this.getUTCSeconds());
};

app.controller('HomeCtrl', function($scope, $state,$rootScope,$http){
	$scope.login = function(){
		$state.go('login')
	}
	$scope.register = function(){
		$state.go('register')
	}
	$rootScope.$on('event:social-sign-in-success', function(event, userDetails){
		console.log(userDetails);// yha lgega na http? yesyaha se succes karake sociallogin.php banadunga same sa
		$http({
            url: "backend/socialLogin.php",
            method: "POST",
            data: $scope.userDetails,
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
app.controller('LoginCtrl', function($scope, $state, $stateParams, $http){
	
	$scope.user = {
		u: '',
		p: ''
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
	//start ....
	$scope.user = {
		u: '',
		p: '',
		e: ''
	}
	$scope.register = function(){
		console.log("User in front end");		
		console.log($scope.user);		
    	$http({
            url: "backend/register.php",
            method: "POST",
            data: $scope.user,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(data, status, headers, config) {
			console.log("User from back end");		
            console.log(data)
        }).error(function(data, status, headers, config) {
            $state.go('home');
        });
    }
})
.controller('LoginHomeCtrl', function($scope, $state, $stateParams,$location, Socialshare){
	$scope.user = $stateParams.user;
	console.log("loginHome"); 
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
    
    })

 /*
tune js kaha se padha tha? javascript.info

 $scope.shareLink = function(type){
        var link = $location.$$absUrl;
        link = link.split("/");
        delete link[link.length - 1];
        link = link.join("/") + "register/" + $scope.user['l_ref'];
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
                if(window.innerWidth <= 480){ 
                    window.open('whatsapp://send?text=Hi! Register on this website using my referral link : ' + link, '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=500,width=700,height=400');
                }else{
                    window.open('https://web.whatsapp.com/send?text=Hi! Register on this website using my referral link : ' + link, '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,top=100,left=500,width=700,height=400');
                }
                break;
        }
    }
*/
   //$scope.reset();

 
/*got?...hume 2 tweens k bich me contact krne k liye use me lete

fir tl ko seek kar sakte hain
kafi uses hain complex timelines banayega tab use me ayenge..ohk boss fb,wa,googl share button ke code memberreg me dekh le
? client id fb ki? nahi isme wo module nahi use hoga
ek aur hai
theek hai?...
1 baar likh do ...
ha ha ha...rhne do....last baat?
ky ho raha h idhar???
kya karna hai apna dekh....me kis rah pe chal raha hu ? 1 word me ?
bhai main khud expermint kar raha hun...,. ha ha hadus.r.e. job le rahe hain to tereko bhi sugeest kardunga ki dhoondh le...
low qm ain tereko isliye time de raha hun kyunki tujhe lagta hai ki tujhe ye karna hai, baaki pata to tujhe hi hai, agar soch liya hai kuch to uss raah par chal de ya to dusri rah pakad le, bas jabardasti mat kar karne ke liye kuch bhi, even web dev agar nahi lag raha hai ki hogfa to dusra kch dhunh le but time mat waste kar kyunki age nikal rahi hai....dara diya apne to pura ....atphoda time dekar khud ko analyze kar...but boss meri performance k hisaab se to lgna chahiye ki mj kahi or interest nh aega ..?

to phir ye hi sugggest karunga ki merese sirf kaam ka pucha kar
dusre kya kar rahe hain main nahi interest leta hun...theek h boss...



ek baat yuad rakh, main na to ye bol raha hun ki tujhe web hi karna hai, na ye bol raha hun ki mere saath hi karna hai, na ye  ki job mat dhoondh

tu puchta hi, mereko pata hai to main bataata hun kitna seekh raha hi, interest aa raha hai ki nahi, aage kaise badhu task me, ye sochna karna tera kaam hai

iske alawa jo bhi sochta, karta hia, sorry but time is money...ohkk boss..krta hu ..

ok, mujhe some months down the line ye nahi sunn ahia ki bali boss ke khne me maine apna career ....get it?...hmmm ..so think hard, investigate koi forcefully nahi kara sakta kaam tere se...h
aan boss chill ... samajh gaya ...
abhi task me kuch puchna hai

memberreg ka code dekh ek baarwo to social share h ... fb ka login 

tu test kaise karge fb acct hai?haan 

ye dekh ek baar samajh aata hai to wrna next option? 
warna you should realise the field is not for you :) bht badiyaaa  ..1 number yo try now. bye bye
*/
/*.controller('RegCtrl', function($scope, $state, $stateParams, $location, $http, Socialshare){
	if($stateParams.user==null){
		$state.go('home');
	}else{
		$scope.user = $stateParams.user;
		$scope.user.r = 'Customer';		
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