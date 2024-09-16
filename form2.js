const app = angular.module('formApp',[]);

app.controller('FormController', ['$scope', '$http', function($scope, $http){
    //adatok inicializálása
    $scope.formData = {};
    $scope.error = '';
    $scope.submissionResult = {};
    $scope.formSubmitted = false; //űrlap alapért.állapota

    //űrlap adatainak küldése a php-nek
    $scope.submitForm = function(){
        //hibák innerhtml-jét kiürítjuk
        $scope.error = '';
        //előző eredmények törlése
        $scope.submissionResult = {};

        //mezők ellenőrzése, ha bármelyik üres nem tudja elküldeni a formot
        if(!$scope.formData.name || !$scope.formData.email || !$scope.formData.phone || !$scope.formData.course ){
            alert("Kérjük, töltse ki a kötelező mezőket!");
            return;
        }

        //adatok küldése
        const formData = new FormData();
            formData.append('name', $scope.formData.name);
            formData.append('email', $scope.formData.email);
            formData.append('phone', $scope.formData.phone);
            formData.append('course', $scope.formData.course);
            formData.append('photo', document.getElementById('photo').files[0]);
            //console.log(formData);
            for(let i of formData.entries()){
                console.log(i[0] + ': ' + i[1]);
            };

            $http.post("form2.php", formData, {
                transformRequest: angular.identity,
                headers : {"Content-Type": undefined}
            })
            .then(function(response){
                $scope.submissionResult = response.data;
                $scope.formSubmitted = true;
            }, function(error){
                $scope.error = "Hiba az adat küldésekor" + error.status + "-" + error.statusText;
            });
    }
}])