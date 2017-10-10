angular.
        module('phoneDetail').
        component('phoneDetail', {
            templateUrl: 'phone-detail/phone-detail.template.html',
            controller: ['Phone', '$routeParams',
                function PhoneDetailController(Phone, $routeParams) {
                    var self = this;

                    self.setImage = function setImage(imageUrl) {
                        self.mainImageUrl = imageUrl;
                    };

                    self.phone = Phone.get({phoneId: $routeParams.phoneId}, function (phone) {
                        self.setImage(phone.images[0]);
                    });
                }
            ]
        });