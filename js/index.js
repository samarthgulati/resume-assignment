var app = angular.module('app', ['ngRoute']);

app.config(function($routeProvider) {
    $routeProvider.when('/detail/:email', {
        templateUrl: 'views/detail.html',
        controller: 'mainController'
    }).when('/skill/:skill', {
        templateUrl: 'views/skill.html',
        controller: 'skillController'
    }).when('/', {
        templateUrl: 'views/list.html',
        controller: 'listController'
    });
})


.controller('mainController',['$scope', '$http', '$routeParams', '$location', function($scope, $http, $routeParams, $location) {

    $http.get('detail.php?email='+$routeParams.email).success(function(data, status) {
        $scope.data = data || "Request failed";
        console.log($scope.data);
    });
    
    $scope.pplWithSkill = function(skill){
        $location.path('/skill/'+ skill);
    };

}]).controller('listController',['$scope', '$http', '$location', function($scope, $http, $location) {
    $http.get('list.php').success(function(data, status) {
        $scope.users = data || "Request failed";
        console.log($scope.users);
    });

    $scope.detail = function (email) {
        $location.path('/detail/'+ email);
    };

}]).controller('skillController',['$scope', '$http', '$routeParams', '$location', function($scope, $http, $routeParams, $location) {
    $scope.skill = $routeParams.skill;
    $http.get('skill.php?skill='+$routeParams.skill).success(function(data, status) {
        $scope.ppl = data || "Request failed";
        console.log($scope.ppl);
    });

    $scope.detail = function (email) {
        $location.path('/detail/'+ email);
    };

}]).filter('tel', function() {
    return function(tel) {
        if (!tel) {
            return '';
        }

        var value = tel.toString().trim().replace(/^\+/, '');

        if (value.match(/[^0-9]/)) {
            return tel;
        }

        var country, city, number;

        switch (value.length) {
            case 10: // +1PPP####### -> C (PPP) ###-####
                country = 1;
                city = value.slice(0, 3);
                number = value.slice(3);
                break;

            case 11: // +CPPP####### -> CCC (PP) ###-####
                country = value[0];
                city = value.slice(1, 4);
                number = value.slice(4);
                break;

            case 12: // +CCCPP####### -> CCC (PP) ###-####
                country = value.slice(0, 3);
                city = value.slice(3, 5);
                number = value.slice(5);
                break;

            default:
                return tel;
        }

        if (country == 1) {
            country = "";
        }

        number = number.slice(0, 3) + '-' + number.slice(3);

        return (country + " (" + city + ") " + number).trim();
    };
}).filter('toISO', function() {
    return function(badTime) {
        var goodTime = badTime.replace(/(.+) (.+)/, "$1T$2Z");
        return goodTime;
    };
});
