function override(o, overrides) {
  if(overrides) {
	for(var method in overrides) {
	  o[method] = overrides[method];
	}
  }
};


override(underground, {
  domReady: function() {
	this.offline = true;
	this.settings.jsBase = '/head/';
	this.settings.mobImagesUrl = '/i/ugmob/';
	this.settings.objectImagesUrl = '/i/ugobj/';
	this.settings.blankImageUrl = '/i/blank.gif';
	this.settings.cellMarkImageUrl = '/i/ugetc/cellmark.gif';
	this.settings.loadingImageUrl = '/i/loading.gif';
	this.settings.compassLocation = '/i/ugetc/compass/';
	this.settings.chapterHeaderPlace = '/i/ugetc/';
	this.offlineFrame = document.getElementById('offline_frame');
	this.offlineFrameSrc = this.offlineFrame.src;
	this.updateFrame = function() {
	  this.offlineFrame.src = this.offlineFrameSrc + '&rnd=' + Math.random();
	};
	setInterval('underground.updateFrame()', 1000 * 60 * 60 * 6);
	this.domReadyCore();
  }
});