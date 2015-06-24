function Paginator() {
    this.rows = null;
    this.url = null;
    this.urlReport = null;
    this.report = null;
    this.data = {
        page: 1,
        limit: 15,
        total: 0,
        rows: 0,
        pages: 0,
        user: 0,
        visit: 0,
        client: 0,
        date: 0
    };
    this.dom;
    this.control = null;
    this.container = 'pagination';
}

Paginator.prototype.setData = function(data) {
    this.data = data;
};

Paginator.prototype.setUrl = function(url) {
    this.url = url;
};

Paginator.prototype.setUrlReport = function(url) {
    this.urlReport = url;
};

Paginator.prototype.setDOM = function(dom) {
    this.dom = dom;
};

Paginator.prototype.setContainerControls = function(pagination) {
    this.pagination = pagination;
};
    
Paginator.prototype.getData = function() {
    var self = this;
	
    return $.Deferred(function(dfd){
        $.ajax({
            url: self.url,
            type: "POST",			
            data: {
                paginator: self.data
            },
            error: function(error){
//                console.log(error.responseText);
                slideOnTop(error.responseText, 5000, 'glyphicon glyphicon-fire', 'error');
                throw error.responseText;
            },
            success: function(data){
                self.refreshData(data);
                dfd.resolve();
            }
        });
    });   
};

Paginator.prototype.getDataForReport = function(url) {
    var self = this;
	
    return $.Deferred(function(dfd){
        $.ajax({
            url: url,
            type: "POST",			
            data: {
                paginator: self.data
            },
            error: function(error){
//                console.log(error.responseText);
                slideOnTop(error.responseText, 5000, 'glyphicon glyphicon-fire', 'error');
                throw error.responseText;
            },
            success: function(data){
                self.report = data;
                dfd.resolve();
            }
        });
    });   
};

Paginator.prototype.refreshData = function(data) {
    this.rows = data.data;
    this.data = {
        page: data.pagination.page,
        limit: data.pagination.limit,
        total: data.pagination.total,
        rows: data.pagination.rows,
        pages: data.pagination.pages,
        user: data.pagination.user,
        visit: data.pagination.visit,
        client: data.pagination.client,
        date: data.pagination.date
    };
};

Paginator.prototype.loadControls = function() {
    this.control = $('<ul class="pagination">\n\
                        <li class="">\n\
                            <a href="javascript:void(0);" class="fast-backward"><i class="glyphicon glyphicon-fast-backward"></i></a>\n\
                        </li>\n\
                        <li class="">\n\
                            <a href="javascript:void(0);" class="step-backward"><i class="glyphicon glyphicon-step-backward"></i></a>\n\
                        </li>\n\
                        <li><span><b id="rows">0</b> registros de <b id="total">0</b></span><span>Página <b id="page">0</b> de <b id="pages">0</b></span></li>\n\
                        <li class="">\n\
                            <a href="javascript:void(0);" class="step-forward"><i class="glyphicon glyphicon-step-forward"></i></a>\n\
                        </li>\n\
                        <li class="">\n\
                            <a href="javascript:void(0);" class="fast-forward"><i class="glyphicon glyphicon-fast-forward"></i></a>\n\
                        </li>\n\
                    </ul>');
    
    $('#' + this.container).append(this.control);
};

Paginator.prototype.refreshControls = function() {
    this.control.find('#rows').empty();
    this.control.find('#total').empty();
    this.control.find('#page').empty();
    this.control.find('#pages').empty();
    this.control.find('#rows').append(this.data.rows);
    this.control.find('#total').append(this.data.total);
    this.control.find('#page').append(this.data.page);
    this.control.find('#pages').append(this.data.pages);
};

Paginator.prototype.catchAndSendData = function(page) {
    var self = this;
    self.data.page = page;
    self.data.limit = $('#limit').val();
    self.data.user = $('#user').val();
    self.data.visit = $('#visittype').val();
    self.data.client = $('#client').val();

    self.getData().then(function() { 
        self.dom.setRows(self.rows);
        self.dom.setData(self.data);
        self.dom.load();
        self.refreshControls();
    });
};

Paginator.prototype.catchAndSendDataForReport = function() {
    var self = this;
    self.data.page = 1;
    self.data.limit = $('#limit').val();
    self.data.user = $('#user').val();
    self.data.visit = $('#visittype').val();
    self.data.client = $('#client').val();

    self.getDataForReport(self.urlReport + "/create").then(function() { 
        $("#loading").hide('slow');
        window.location = self.urlReport + '/download/' + self.report[0];
    });
};

Paginator.prototype.catchAndSendDataForFullReport = function() {
    var self = this;
    self.getDataForReport(self.urlReport + "/createfull").then(function() { 
        $("#loading").hide('slow');
        window.location = self.urlReport + '/download/' + self.report[0];
    });
};

Paginator.prototype.initialize = function() {
    var self = this;
    this.control.on("click", ".fast-backward", function () {
        self.catchAndSendData(1);
    });
    
    this.control.on("click", ".step-backward", function () {
        var page = parseInt(self.data.page) - 1;
        if (page > 0) {
            self.catchAndSendData(page);
        }
    });
    
    this.control.on("click", ".step-forward", function () {
        var page = parseInt(self.data.page) + 1;
        if (page <= self.data.pages) {
            self.catchAndSendData(page);
        }
    });
    
    this.control.on("click", ".fast-forward", function () {
        var page = self.data.pages;
        if (page !== self.data.pages) {
            self.catchAndSendData(page);
        }
    });
    
    $( "#refresher" ).click(function() {
        self.catchAndSendData(1);
    });
    
    $( "#filter-downloader" ).click(function() {
        self.catchAndSendDataForReport();
    });
    
    $( "#downloader" ).click(function() {
        self.catchAndSendDataForFullReport();
    });
};

Paginator.prototype.load = function() {
    this.loadControls();
    var self = this;
    this.getData().then(function() { 
        self.dom.setRows(self.rows);
        self.dom.setData(self.data);
        self.dom.createTable();
        self.dom.load();
        self.refreshControls();
        self.initialize();
    });
    
};