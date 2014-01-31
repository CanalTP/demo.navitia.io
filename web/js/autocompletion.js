
var AutocompleteEngine = Base.extend
({
	Text: '',
	FieldIdPrefix: '',
	FlContainerId: '',
	ResultContainer: '',
	ResultItemContainer: '',
	CrossDomainUrl: '',
	PlaceTypeLabels: null,
	ResultList: null,
	ResultCurrentOrder: 0,
	ResultPreviousOrder: 0,
	PlaceList: null,
	CallbackFunctions: {},
	CallbackAttributes: {}, 
	ObjectFilter: ['stop_area', 'address', 'poi'],

	constructor: function(crossDomainUrl, containerUrl, itemContainerUrl)
	{
		this.Text = '';
		this.CrossDomainUrl = crossDomainUrl;
		this.getResultContainerTemplate(containerUrl);
		this.getResultItemContainerTemplate(itemContainerUrl);
		this.ResultList = new Array();
	},
	
	setCallbackAttributes: function(data)
	{
	    this.CallbackAttributes = data;
	},
	
	setObjectFilter: function(filterArray)
	{
	    this.ObjectFilter = filterArray;
	},
	
	setCallbackFunctions: function(callbackJson)
	{
	    this.CallbackFunctions = {
	        onResult: callbackJson.onResult,
	        onClick: callbackJson.onClick,
	        onErase: callbackJson.onErase
	    };
	},

	setPlaceTypeLabels: function(labelListData)
	{
		this.PlaceTypeLabels = labelListData;
	},
	
	setAdminTypeLabels: function(adminListData)
	{
	    this.AdminTypeLabels = adminListData;
	},

	getResultContainerTemplate: function(url)
	{
	    this.ResultContainer = '';
	    var that = this;
	    $.ajax({
		    'url': url,
		    'dataType': 'html',
		    'success': function(data) {
		        that.ResultContainer = data;
		    }
	    });
	},

    getResultItemContainerTemplate: function(url)
    {
        this.ResultItemContainer = '';
        var that = this;
        $.ajax({
            'url': url,
            'dataType': 'html',
            'success': function(data) {
                that.ResultItemContainer = data;
            },
        });
    },

    bind: function(fieldIdPrefix, flContainerId)
    {
        var that = this;
    
        this.FieldIdPrefix = fieldIdPrefix;
        this.FlContainerId = flContainerId;
    
        $('#' + fieldIdPrefix + '_name').attr('autocomplete', 'off');
        $('html').live('click', function() {
            //console.log('html.click');
            $('#' + flContainerId).hide();
        });
        $('#' + flContainerId).bind('click', function(event) {
            //console.log('fl.click');
            event.stopPropagation();
        });

        $('#' + fieldIdPrefix + '_name').live('keyup', function(event) {
            window.setInterval(function() { that.responseListener(that.ResultCurrentOrder) }, 50);
            var text = $(this).val();
            var result = null;
            if (text) {
                var filterString = '';
                for (var i in that.ObjectFilter) {
                    filterString += '&type[]=' + that.ObjectFilter[i];
                }
                // Il y a du texte dans le champ
                var url = that.CrossDomainUrl + '/places.json?nbmax=5' + filterString + '&q=' + encodeURIComponent(text);
                that.ResultCurrentOrder++;
                $.ajax({
                    'url': url,
                    'dataType': 'json',
                    'success': function(data) {
                        result = that.parseNavitiaResult(data);
                        that.ResultList[that.ResultCurrentOrder] = {
                            'resultHtml': result,
                            'order': that.ResultCurrentOrder
                        };
                    }
                });
            } else {
                // L'utilisateur a vidé le champ
                that.eraseResult();
                $('#' + flContainerId).hide();
            }
        });

        $('#' + fieldIdPrefix + '_name').live('keydown', function(event) {
            switch (event.keyCode) {
                case 38:
                    break;
                case 40:
                    break;
            }
        });
    },

    responseListener: function(order)
    {
        if (typeof(this.ResultList[order]) !== 'undefined') {
            if (this.ResultList[order].resultHtml !== '') {
                if (order !== this.ResultPreviousOrder) {
                    this.pushResult(this.ResultList[order].resultHtml);
                    this.ResultPreviousOrder = order;
                }
            }
        }
    },

    parseNavitiaResult: function(result)
    {
        var nameList = this.ResultContainer.split('%ITEMS%');
        var that = this;
        if (result !== null && typeof(result.places) !== 'undefined') {
            for (i in result.places) {
                var itemHtml = that.ResultItemContainer;
                if (typeof(result.places[i]) !== 'undefined') {
                    itemHtml = this.parseMetaData(itemHtml, i);
                    itemHtml = this.parseCssClass(itemHtml, i, result.places[i]);
                    itemHtml = this.parseObjectTypeName(itemHtml, result.places[i]);
                    itemHtml = this.parseObjectName(itemHtml, result.places[i]);
                    itemHtml = this.parseAdminName(itemHtml, result.places[i]);
                    itemHtml = this.parseAdminZipCode(itemHtml, result.places[i]);
                    itemHtml = this.parseUri(itemHtml, result.places[i]);
                    nameList[0] += itemHtml;
                }
            }
            if (typeof(this.CallbackFunctions.onResult) === 'function') {
                var place = result.places[0];
                place.type = this.getPlaceType(place);
                this.CallbackFunctions.onResult(place, this.CallbackAttributes);
            }
            this.PlaceList = result.places;
            return nameList[0] + nameList[1];
        } else {
            return null;
        }
    },
    
    parseMetaData: function(html, index)
    {
        html = html.split('%ENTRY_TYPE%');
        html = html[0] + this.FieldIdPrefix + '_name' + html[1];
        
        html = html.split('%INDEX%');
        return html[0] + index + html[1];
    },

    parseCssClass: function(html, index, place)
    {
        html = html.split('%CLASS%');
        var classValue = '';
        if (index % 2 === 1) {
            classValue += 'odd ';
        } else {
            classValue += 'even ';
        }
        classValue += 'type_' + this.getPlaceType(place).toLowerCase();

        return html[0] + classValue.trim() + html[1];
    },

    parseObjectTypeName: function(html, place)
    {
        var type = this.getPlaceType(place);
        html = html.split('%TYPE_NAME%');
        
        html = html[0] + this.PlaceTypeLabels[type] + html[1];
        
        return html;
    },

    parseObjectName: function(html, place)
    {
        var type = this.getPlaceType(place);
        var placeName = place.name;
        
        html = html.split('%NAME%');
        
        switch (type) {
            case 'STOP_AREA':
                placeName = place.stop_area.name;
                break;
            case 'STOP_POINT':
                placeName = place.stop_point.name;
                break;
            case 'POI':
                placeName = place.poi.name;
                break;
            case 'ADDRESS':
                placeName = place.address.name;
                break;
            case 'ADMIN':
                placeName = place.administrative_region.name;
                break;
        }
        html = html[0] + placeName + html[1];
        
        return html;
    },

    parseAdminName: function(html, place)
    {
        html = html.split('%CITY_NAME%');

        switch (this.getPlaceType(place)) {
            case 'STOP_AREA':
                html = html[0] + this.getAdminLabel(place.stop_area.administrative_regions) + html[1];
                break;
            case 'STOP_POINT':
                html = html[0] + this.getAdminLabel(place.stop_point.administrative_regions) + html[1];
                break;
            case 'ADDRESS':
                html = html[0] + this.getAdminLabel(place.address.administrative_regions) + html[1];
                break;
            case 'POI':
                html = html[0] + this.getAdminLabel(place.poi.administrative_regions) + html[1];
                break;
            case 'ADMIN':
                html = html[0] + this.getAdminLabel(place.administrative_region) + html[1];
                break;
            default:
                html = html[0] + '---' + html[1];
            break;
        }
        return html;
    },

    parseAdminZipCode: function(html, place)
    {
        html = html.split('%CITY_CODE%');
        var code = '';

        switch (this.getPlaceType(place)) {
            case 'STOP_AREA':
                code = this.getAdminZipCode(place.stop_area.administrative_regions);
                break;
            case 'STOP_POINT':
                code = this.getAdminZipCode(place.stop_point.administrative_regions);
                break;
            case 'ADDRESS':
                code = this.getAdminZipCode(place.address.administrative_regions);
                break;
            case 'POI':
                code = this.getAdminZipCode(place.poi.administrative_regions);
                break;
            case 'ADMIN':
                code = this.getAdminZipCode(place.administrative_region);
                break;
            default:
                break;
        }
        
        if (code !== '') {
            code = '(' + code + ')';
        }
        html = html[0] + code + html[1];
        
        return html;
    },

    parseUri: function(html, place)
    {
        html = html.split('%URI%');
        if (typeof(place.uri) !== 'undefined') {
            html = html[0] + place.uri + html[1];
        } else {
            html = html[0] + html[1];
        }

        return html;
    },

    /**
     * Permet d'identifier le type de point
     */
    getPlaceType: function(place)
    {
        if (typeof(place.address) !== 'undefined') {
            return 'ADDRESS';
        } else if (typeof(place.stop_area) !== 'undefined') {
            return 'STOP_AREA';
        } else if (typeof(place.stop_point) !== 'undefined') {
            return 'STOP_POINT';
        } else if (typeof(place.poi) !== 'undefined') {
            return 'POI';
        } else if (typeof(place.administrative_region) !== 'undefined') {
            return 'ADMIN';
        }
        return 'UNKNOWN';
    },
    
    getPlaceCoords: function(place)
    {
        switch (this.getPlaceType(place)) {
            case 'ADDRESS':
                if (typeof(place.address.coord) !== 'undefined') {
                    return place.address.coord.lon + ';' + place.address.coord.lat;
                }
                break;
            case 'STOP_AREA':
                if (typeof(place.stop_area.coord) !== 'undefined') {
                    return place.stop_area.coord.lon + ';' + place.stop_area.coord.lat;
                }
                break;
            case 'STOP_POINT':
                if (typeof(place.stop_point.coord) !== 'undefined') {
                    return place.stop_point.coord.lon + ';' + place.stop_point.coord.lat;
                }
                break;
            case 'POI':
                if (typeof(place.poi.coord) !== 'undefined') {
                    return place.poi.coord.lon + ';' + place.poi.coord.lat;
                }
                break;
            case 'ADMIN':
                if (typeof(place.administrative_region.coord) !== 'undefined') {
                    return place.administrative_region.coord.lon + ';' + place.administrative_region.coord.lat;
                }
                break;
        }
        return '';
    },

    eraseResult: function()
    {
        if (typeof(this.CallbackFunctions.onErase) === 'function') {
            this.CallbackFunctions.onErase(this.CallbackAttributes);
        }
        $('#' + this.FLContainerId).html('');
    },

    pushResult: function(result)
    {
        if (result !== null) {
            $('#' + this.FlContainerId).show();
            $('#' + this.FlContainerId).html(result);
            this.bindValidateItem();
        } else {
            this.eraseResult();
        }
    },

    bindValidateItem: function()
    {
        var that = this;
        $('.fletter_item_link').bind('click', function() {
            var index = $(this).parent().attr('id').split('-');
            var place = that.PlaceList[index[1]];
            $('#' + that.FieldIdPrefix + '_name').val($(this).find('.fletter_item_name').text());
            $('#' + that.FieldIdPrefix + '_uri').val($(this).attr('id'));
            $('#' + that.FieldIdPrefix + '_coords').val(that.getPlaceCoords(place));
            $('#' + that.FlContainerId).hide();
            if (place) {
                place.type = that.getPlaceType(place);
                if (typeof(that.CallbackFunctions.onClick) === 'function') {
                    that.CallbackFunctions.onClick(place, that.CallbackAttributes);
                }
            }
        });
    },
    
    getAdminZipCode: function(adminList)
    {
        var code = '';
        for (var i in adminList) {
            if (adminList[i].zip_code !== '') {
                code = adminList[i].zip_code;
            }
        }
        return code;
    },
    
    getAdminLabel: function(adminList)
    {
        var label = '';
        for (var i in adminList) {
            if (adminList[i].name !== '') {
                label = adminList[i].name;
            }
        }
        return label;
    }
});