'use strict';

angular.module('Microshop.version', [
  'Microshop.version.interpolate-filter',
  'Microshop.version.version-directive'
])

.value('version', '0.1');
