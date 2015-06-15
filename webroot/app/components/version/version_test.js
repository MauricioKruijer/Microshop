'use strict';

describe('Microshop.version module', function() {
  beforeEach(module('Microshop.version'));

  describe('version service', function() {
    it('should return current version', inject(function(version) {
      expect(version).toEqual('0.1');
    }));
  });
});
