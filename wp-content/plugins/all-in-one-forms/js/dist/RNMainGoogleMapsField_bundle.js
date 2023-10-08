rndefine("#RNMainGoogleMapsField",["exports","#RNMainFormBuilderCore/FieldWithPrice.Model","lit","#RNMainCore/SingleEvent","#RNMainCoreUI/ModelWithParent","#RNMainCore/StoreBase","#RNMainFormBuilderCore/CalculatorBase","#RNMainCore/Sanitizer","lit/decorators","#RNMainFormBuilderCore/FieldBase","#RNMainFormBuilderCore/FieldWithPrice","#RNMainFormBuilderCore/FieldBase.Options","#RNMainFormBuilderCore/FieldWithPrice.Options","#RNMainCore/EventManager","#RNMainCore/LitElementBase","#RNMainLit/Lit","lit/directives/ref.js","lit-html/directives/live.js","#RNMainFormBuilderCore/RunnableComparatorBase","#RNMainFormBuilderCore/ConditionBase.Options"],(function(e,a,t,s,n,r,o,l,i,d,N,m,C,T,R,u,h,p,M,c){"use strict";class S extends r.StoreBase{LoadDefaultValues(){this.MarkerLatitude=0,this.MarkerLongitude=0,this.Address=(new g).Merge()}}class g extends r.StoreBase{LoadDefaultValues(){this.StreetAddress1="",this.StreetAddress2="",this.City="",this.State="",this.Zip="",this.CountryShort="",this.CountryLong=""}}class y extends n.ModelWithParent{constructor(e,a){super(e,a),this.Geocoder=null,this.InfoWindow=null,this.Marker=null}InitializeGoogleMaps(){this.Geocoder=new window.google.maps.Geocoder,this.InfoWindow=new window.google.maps.InfoWindow({size:new window.google.maps.Size(150,50)}),this.Marker=new window.google.maps.Marker({map:this.Parent.Map,position:null}),window.google.maps.event.addListener(this.Marker,"click",(()=>{this.Marker.setMap(null),this.Options.Address=(new g).Merge(),this.Options.MarkerLongitude=0,this.Options.MarkerLatitude=0,this.Parent.MaybeCalculatePath(),this.Refresh()}))}get IsEmpty(){return 0==this.Options.MarkerLongitude&&0==this.Options.MarkerLongitude&&""==this.Options.Address.StreetAddress1}CalculateAddress(){this.Options.Address.FormattedAddress="",this.Geocoder.geocode({latLng:this.Marker.getPosition()},(e=>{null!=e&&e.length>0&&(this.Options.Address.FormattedAddress=e[0].formatted_address,this.InfoWindow.setContent(this.Options.Address.FormattedAddress),this.Parent.Options.ShowMarkerAddress&&this.InfoWindow.open(this.Marker.getMap(),this.Marker),null==this.Options.Address&&(this.Options.Address=(new g).Merge()),this.UpdateAddress(this.Options.Address,e[0].address_components))}))}GetText(){if(""==this.Options.Address.StreetAddress1)return"";let e=[];return""!=this.Options.Address.StreetAddress1&&e.push(this.Options.Address.StreetAddress1),""!=this.Options.Address.StreetAddress2&&this.Parent.Options.ShowAddress2&&e.push(this.Options.Address.StreetAddress2),""!=this.Options.Address.City&&this.Parent.Options.ShowCity&&e.push(this.Options.Address.City),""!=this.Options.Address.State&&this.Parent.Options.ShowState&&e.push(this.Options.Address.State),""!=this.Options.Address.Zip&&this.Parent.Options.ShowZip&&e.push(this.Options.Address.Zip),""!=this.Options.Address.CountryLong&&this.Parent.Options.ShowCountry&&e.push(this.Options.Address.CountryLong),e.join("\n ")}UpdateAddress(e=null,a=null){let t,s=this.AutoComplete.getPlace();null==a&&(a=s.address_components),t=null==e?this.Options.Address:e,t.StreetAddress1="",t.StreetAddress1="",t.City="",t.State="",t.CountryLong="",t.CountryShort="",t.Zip="",t.Latitude=0,t.Longitude=0,t.MarkerLatitude=0,t.MarkerLongitude=0,t.Zoom=0;for(let e of a){let a=e.types[0],s=e.long_name;"street_number"==a&&(t.StreetAddress1=s),"route"==a&&(t.StreetAddress1+=" "+s),"sublocality_level_1"==a&&(t.StreetAddress2=s),"locality"==a&&(t.City=s),"administrative_area_level_1"==a&&(t.State=s),"postal_code"==a&&(t.Zip=s),"country"==a&&(t.CountryLong=s,t.CountryShort=e.short_name)}""==t.StreetAddress1&&(t.StreetAddress1=t.StreetAddress2,t.StreetAddress2=""),this.Parent.Options,this.Parent.FireValueChanged()}setPosition(e){this.Options.MarkerLatitude=e.lat(),this.Options.MarkerLongitude=e.lng(),this.Marker.setMap(this.Parent.Map),this.Marker.setPosition(e)}}class A extends o.CalculatorBase{ExecuteCalculation(e){let a=this.Field,t=a.Distance,s=0;return t>0&&("Kilometer"==a.Options.DistanceType&&(s=t/1e3*a.Options.PricePerDistance),"Meter"==a.Options.DistanceType&&(s=t*a.Options.PricePerDistance),"Mile"==a.Options.DistanceType&&(s=.0006213712*t*a.Options.PricePerDistance)),{Quantity:1,RegularPrice:s,SalePrice:0}}}class b extends a.FieldWithPriceModel{constructor(e,a){super(e,a),this.ThrottleId=0,this.RequestId=0,this.IsFocused=!1,this.Markers=[],this.Directions=[],this.Distance=0}async Initialize(e=null){return await this.InitializeGoogleMaps(),super.Initialize(e)}InitializeMarkers(){if(!(this.Markers.length>0)){for(let a=0;a<this.Options.NumberOfMarkers;a++){let a=new y((new S).Merge(),this);var e;if(""!=this.Options.DefaultCountry)a.Options.Address.CountryShort=this.Options.DefaultCountry,a.Options.Address.CountryLong=null===(e=V.find((e=>e.Code==this.Options.DefaultCountry)))||void 0===e?void 0:e.Name;this.Markers.push(a)}this.Markers.forEach((e=>e.InitializeGoogleMaps())),this.Refresh()}}GetText(){let e="";for(let a=0;a<this.Markers.length;a++)a>0&&(e+="\n\n"),e+=this.Markers[a].GetText();return e}InitializePriceCalculator(){if("distance"==this.Options.PriceType)return this.Calculator=new A,void this.Calculator.Initialize(this);super.InitializePriceCalculator()}InternalSerialize(e){super.InternalSerialize(e),e.Value=this.GetValue(),e.Value.Address1Label=this.Options.Address1Label,e.Value.Address2Label=this.Options.Address2Label,e.Value.CityLabel=this.Options.CityLabel,e.Value.StateLabel=this.Options.StateLabel,e.Value.ZipLabel=this.Options.ZipLabel,e.Value.CountryLabel=this.Options.CountryLabel}SetDistance(e){this.Distance=e,this.FireValueChanged()}GetStoresInformation(){return!0}GetIsUsed(){return!(this.Markers.some((e=>e.IsEmpty))||!super.GetIsUsed())}GetValue(){return this.GetIsVisible()&&this.GetIsUsed()?{Longitude:this.Longitude,Latitude:this.Latitude,Zoom:this.Zoom,MarkerLatitude:this.MarkerLatitude,MarkerLongitude:this.MarkerLongitude,Markers:this.Markers.map((e=>e.Options.ToObject())),Distance:this.Distance}:null}SetLastMarket(e){Math.max(1,this.Options.NumberOfMarkers);let a=this.Markers.find((e=>e.IsEmpty));null==a&&(a=this.Markers[this.Markers.length-1]),null!=a&&this.SetMarker(a,e)}SetMarker(e,a,t=!1){e.setPosition(a),t||e.CalculateAddress(),e.Marker.setVisible(!0),this.SaveCanvas(null),this.MaybeCalculatePath(),setTimeout((()=>this.SaveCanvas(null)),300)}MaybeCalculatePath(){this.RequestId++;let e=this.RequestId,a=new window.google.maps.DirectionsService;for(let e of this.Directions)e.setMap(null);this.Directions=[];let t=Math.max(1,this.Options.NumberOfMarkers);this.Distances=[],this.SetDistance(0);for(let s=1;s<this.Markers.length;s++){let n=this.Markers[s-1].Marker,r=this.Markers[s].Marker;if(null==n.map||null==r.map)continue;let o={origin:new window.google.maps.LatLng(n.getPosition().lat(),n.getPosition().lng()),destination:new window.google.maps.LatLng(r.getPosition().lat(),r.getPosition().lng()),travelMode:window.google.maps.TravelMode.DRIVING},l=new window.google.maps.DirectionsRenderer({suppressMarkers:!0});this.Directions.push(l),l.setMap(this.Map),a.route(o,((a,s)=>{this.RequestId==e&&s==window.google.maps.DirectionsStatus.OK&&(this.Distances.push(a.routes[0].legs[0].distance.value),this.Distances.length==t-1&&this.SetDistance(this.Distances.reduce(((e,a)=>e+a),0)),this.Options.ConnectPoints&&l.setDirections(a))}))}}SaveCanvas(e){null!=e&&e.preventDefault(),this.Latitude=this.Map.getCenter().lat(),this.Longitude=this.Map.getCenter().lng(),this.Zoom=this.Map.getZoom(),this.ThrottleId++}InitializeStartingValues(){this.Latitude=this.GetPreviousDataProperty("Latitude",0),this.Longitude=this.GetPreviousDataProperty("Longitude",0),this.Quantity=this.GetPreviousDataProperty("Quantity",""==this.Options.QuantityDefaultValue?"":l.Sanitizer.SanitizeNumber(this.Options.QuantityDefaultValue))}GetDynamicFieldNames(){return["FBGoogleMaps"]}InitializeGoogleMaps(){0==b.GMapsIsLoaded?(b.GoogleMapsScriptLoaded.Subscribe(this,(()=>this.ScriptInitializationCompleted())),b.LoadMapScript(this.FormBuilder.RootFormBuilder.AdditionalOptions.GoogleMapsApiKey)):this.ScriptInitializationCompleted()}static LoadMapScript(e){b.IsLoading||(null!=b.GMapScript&&b.GMapScript.remove(),b.IsLoading=!0,b.GMapScript=document.createElement("script"),b.GMapScript.type="text/javascript",b.GMapScript.src="https://maps.googleapis.com/maps/api/js?places=&libraries=places&key="+e,b.GMapScript.addEventListener("load",(()=>{b.IsLoading=!1,b.GMapsIsLoaded=!0,this.GoogleMapsScriptLoaded.Publish()})),document.body.appendChild(b.GMapScript))}ScriptInitializationCompleted(){this.Refresh()}render(){return t.html`<rn-google-maps-field .model="${this}"></rn-google-maps-field>`}}var L;b.GMapsIsLoaded=!1,b.GoogleMapsScriptLoaded=new s.SingleEvent,b.IsLoading=!1;let G=i.customElement("rn-google-maps-field")(L=class extends N.FieldWithPrice{static get properties(){return d.FieldBase.properties}SubRender(){return t.html` <div style="position: relative;"> ${0==b.GMapsIsLoaded?t.html`<span>Loading google maps</span>`:t.html`<rn-google-maps-address-control .Model=${this.model}></rn-google-maps-address-control>`} </div> `}})||L;var I,v,P,O,w;let B=(I=r.StoreDataType(String),v=r.StoreDataType(String),P=class extends C.FieldWithPriceOptions{constructor(...e){super(...e),babelHelpers.initializerDefineProperty(this,"RestrictedCountries",O,this),babelHelpers.initializerDefineProperty(this,"MarkerLabels",w,this)}LoadDefaultValues(){super.LoadDefaultValues(),this.Type=m.FieldTypeEnum.GoogleMaps,this.Label="Google Maps",this.DefaultCountry="",this.RestrictedCountries=[],this.NumberOfMarkers=1,this.ShowAddress2=!0,this.ShowCity=!0,this.ShowState=!0,this.ShowZip=!0,this.ShowCountry=!0,this.ShowQuantitySelector=!1,this.QuantityPosition="bottom",this.QuantityMaximumValue=0,this.QuantityMinimumValue=0,this.QuantityDefaultValue="",this.QuantityPlaceholder="",this.QuantityLabel="Quantity",this.ConnectPoints=!1,this.Address1Label="Address 1",this.Address2Label="Address 2",this.CityLabel="City",this.StateLabel="State",this.ZipLabel="Zip",this.CountryLabel="Country",this.ShowMap=!0,this.PriceType=C.PriceTypeEnum.none,this.ShowMarkerAddress=!1,this.DistanceType="Kilometer",this.PricePerDistance=1,this.MarkerLabels=[]}},O=babelHelpers.applyDecoratedDescriptor(P.prototype,"RestrictedCountries",[I],{configurable:!0,enumerable:!0,writable:!0,initializer:null}),w=babelHelpers.applyDecoratedDescriptor(P.prototype,"MarkerLabels",[v],{configurable:!0,enumerable:!0,writable:!0,initializer:null}),P);class f extends M.RunnableComparatorBase{InternalCompare(e,a){let t=l.Sanitizer.SanitizeString(e.Value),s=a.GetValue();switch(s=null==s?[]:s.Markers.map((a=>l.Sanitizer.GetValueFromPath(a,["Address",e.PathId],null))),e.Comparison){case c.ComparisonTypeEnum.Equal:return s.some((e=>e==t));case c.ComparisonTypeEnum.NotEqual:return s.some((e=>t!=e));case c.ComparisonTypeEnum.IsEmpty:return 0==s.length;case c.ComparisonTypeEnum.IsNotEmpty:return s.length>0;case c.ComparisonTypeEnum.Contains:return s.some((e=>e.toLocaleLowerCase().indexOf(t.toLocaleLowerCase())>=0));case c.ComparisonTypeEnum.NotContains:return s.some((e=>e.toLocaleLowerCase().indexOf(t.toLocaleLowerCase())<0))}}}T.EventManager.Subscribe("GetRunnableComparator",(e=>{if("GoogleMaps"==e.SubType)return new f(e.Container)})),e.GoogleMapsFieldModel=b,e.GoogleMapsField=G,e.GoogleMapsFieldOptions=B,e.GoogleMapsRunnableComparator=f,T.EventManager.Subscribe("GetFieldOptions",(e=>{if(e==m.FieldTypeEnum.GoogleMaps)return new B})),T.EventManager.Subscribe("GetFieldModel",(e=>{if(e.Options.Type==m.FieldTypeEnum.GoogleMaps)return new b(e.Options,e.Parent)}));var k,E,D,F,K=[{Code:"AF",Name:RNTranslate("Afghanistan")},{Code:"AL",Name:RNTranslate("Albania")},{Code:"DZ",Name:RNTranslate("Algeria")},{Code:"AS",Name:RNTranslate("American Samoa")},{Code:"AD",Name:RNTranslate("Andorra")},{Code:"AO",Name:RNTranslate("Angola")},{Code:"AI",Name:RNTranslate("Anguilla")},{Code:"AQ",Name:RNTranslate("Antarctica")},{Code:"AG",Name:RNTranslate("Antigua & Barbuda")},{Code:"AR",Name:RNTranslate("Argentina")},{Code:"AM",Name:RNTranslate("Armenia")},{Code:"AW",Name:RNTranslate("Aruba")},{Code:"AC",Name:RNTranslate("Ascension Island")},{Code:"AU",Name:RNTranslate("Australia")},{Code:"AT",Name:RNTranslate("Austria")},{Code:"AZ",Name:RNTranslate("Azerbaijan")},{Code:"BS",Name:RNTranslate("Bahamas")},{Code:"BH",Name:RNTranslate("Bahrain")},{Code:"BD",Name:RNTranslate("Bangladesh")},{Code:"BB",Name:RNTranslate("Barbados")},{Code:"BY",Name:RNTranslate("Belarus")},{Code:"BE",Name:RNTranslate("Belgium")},{Code:"BZ",Name:RNTranslate("Belize")},{Code:"BJ",Name:RNTranslate("Benin")},{Code:"BM",Name:RNTranslate("Bermuda")},{Code:"BT",Name:RNTranslate("Bhutan")},{Code:"BO",Name:RNTranslate("Bolivia")},{Code:"BA",Name:RNTranslate("Bosnia & Herzegovina")},{Code:"BW",Name:RNTranslate("Botswana")},{Code:"BV",Name:RNTranslate("Bouvet Island")},{Code:"BR",Name:RNTranslate("Brazil")},{Code:"IO",Name:RNTranslate("British Indian Ocean Territory")},{Code:"VG",Name:RNTranslate("British Virgin Islands")},{Code:"BN",Name:RNTranslate("Brunei")},{Code:"BG",Name:RNTranslate("Bulgaria")},{Code:"BF",Name:RNTranslate("Burkina Faso")},{Code:"BI",Name:RNTranslate("Burundi")},{Code:"KH",Name:RNTranslate("Cambodia")},{Code:"CM",Name:RNTranslate("Cameroon")},{Code:"CA",Name:RNTranslate("Canada")},{Code:"IC",Name:RNTranslate("Canary Islands")},{Code:"CV",Name:RNTranslate("Cape Verde")},{Code:"BQ",Name:RNTranslate("Caribbean Netherlands")},{Code:"KY",Name:RNTranslate("Cayman Islands")},{Code:"CF",Name:RNTranslate("Central African Republic")},{Code:"EA",Name:RNTranslate("Ceuta & Melilla")},{Code:"TD",Name:RNTranslate("Chad")},{Code:"CL",Name:RNTranslate("Chile")},{Code:"CN",Name:RNTranslate("China")},{Code:"CX",Name:RNTranslate("Christmas Island")},{Code:"CP",Name:RNTranslate("Clipperton Island")},{Code:"CC",Name:RNTranslate("Cocos (Keeling) Islands")},{Code:"CO",Name:RNTranslate("Colombia")},{Code:"KM",Name:RNTranslate("Comoros")},{Code:"CG",Name:RNTranslate("Congo - Brazzaville")},{Code:"CD",Name:RNTranslate("Congo - Kinshasa")},{Code:"CK",Name:RNTranslate("Cook Islands")},{Code:"CR",Name:RNTranslate("Costa Rica")},{Code:"HR",Name:RNTranslate("Croatia")},{Code:"CU",Name:RNTranslate("Cuba")},{Code:"CW",Name:RNTranslate("Curaçao")},{Code:"CY",Name:RNTranslate("Cyprus")},{Code:"CZ",Name:RNTranslate("Czechia")},{Code:"CI",Name:RNTranslate("Côte d’Ivoire")},{Code:"DK",Name:RNTranslate("Denmark")},{Code:"DG",Name:RNTranslate("Diego Garcia")},{Code:"DJ",Name:RNTranslate("Djibouti")},{Code:"DM",Name:RNTranslate("Dominica")},{Code:"DO",Name:RNTranslate("Dominican Republic")},{Code:"EC",Name:RNTranslate("Ecuador")},{Code:"EG",Name:RNTranslate("Egypt")},{Code:"SV",Name:RNTranslate("El Salvador")},{Code:"GQ",Name:RNTranslate("Equatorial Guinea")},{Code:"ER",Name:RNTranslate("Eritrea")},{Code:"EE",Name:RNTranslate("Estonia")},{Code:"SZ",Name:RNTranslate("Eswatini")},{Code:"ET",Name:RNTranslate("Ethiopia")},{Code:"FK",Name:RNTranslate("Falkland Islands (Islas Malvinas)")},{Code:"FO",Name:RNTranslate("Faroe Islands")},{Code:"FJ",Name:RNTranslate("Fiji")},{Code:"FI",Name:RNTranslate("Finland")},{Code:"FR",Name:RNTranslate("France")},{Code:"GF",Name:RNTranslate("French Guiana")},{Code:"PF",Name:RNTranslate("French Polynesia")},{Code:"TF",Name:RNTranslate("French Southern Territories")},{Code:"GA",Name:RNTranslate("Gabon")},{Code:"GM",Name:RNTranslate("Gambia")},{Code:"GE",Name:RNTranslate("Georgia")},{Code:"DE",Name:RNTranslate("Germany")},{Code:"GH",Name:RNTranslate("Ghana")},{Code:"GI",Name:RNTranslate("Gibraltar")},{Code:"GR",Name:RNTranslate("Greece")},{Code:"GL",Name:RNTranslate("Greenland")},{Code:"GD",Name:RNTranslate("Grenada")},{Code:"GP",Name:RNTranslate("Guadeloupe")},{Code:"GU",Name:RNTranslate("Guam")},{Code:"GT",Name:RNTranslate("Guatemala")},{Code:"GG",Name:RNTranslate("Guernsey")},{Code:"GN",Name:RNTranslate("Guinea")},{Code:"GW",Name:RNTranslate("Guinea-Bissau")},{Code:"GY",Name:RNTranslate("Guyana")},{Code:"HT",Name:RNTranslate("Haiti")},{Code:"HM",Name:RNTranslate("Heard & McDonald Islands")},{Code:"HN",Name:RNTranslate("Honduras")},{Code:"HK",Name:RNTranslate("Hong Kong")},{Code:"HU",Name:RNTranslate("Hungary")},{Code:"IS",Name:RNTranslate("Iceland")},{Code:"IN",Name:RNTranslate("India")},{Code:"ID",Name:RNTranslate("Indonesia")},{Code:"IR",Name:RNTranslate("Iran")},{Code:"IQ",Name:RNTranslate("Iraq")},{Code:"IE",Name:RNTranslate("Ireland")},{Code:"IM",Name:RNTranslate("Isle of Man")},{Code:"IL",Name:RNTranslate("Israel")},{Code:"IT",Name:RNTranslate("Italy")},{Code:"JM",Name:RNTranslate("Jamaica")},{Code:"JP",Name:RNTranslate("Japan")},{Code:"JE",Name:RNTranslate("Jersey")},{Code:"JO",Name:RNTranslate("Jordan")},{Code:"KZ",Name:RNTranslate("Kazakhstan")},{Code:"KE",Name:RNTranslate("Kenya")},{Code:"KI",Name:RNTranslate("Kiribati")},{Code:"XK",Name:RNTranslate("Kosovo")},{Code:"KW",Name:RNTranslate("Kuwait")},{Code:"KG",Name:RNTranslate("Kyrgyzstan")},{Code:"LA",Name:RNTranslate("Laos")},{Code:"LV",Name:RNTranslate("Latvia")},{Code:"LB",Name:RNTranslate("Lebanon")},{Code:"LS",Name:RNTranslate("Lesotho")},{Code:"LR",Name:RNTranslate("Liberia")},{Code:"LY",Name:RNTranslate("Libya")},{Code:"LI",Name:RNTranslate("Liechtenstein")},{Code:"LT",Name:RNTranslate("Lithuania")},{Code:"LU",Name:RNTranslate("Luxembourg")},{Code:"MO",Name:RNTranslate("Macao")},{Code:"MG",Name:RNTranslate("Madagascar")},{Code:"MW",Name:RNTranslate("Malawi")},{Code:"MY",Name:RNTranslate("Malaysia")},{Code:"MV",Name:RNTranslate("Maldives")},{Code:"ML",Name:RNTranslate("Mali")},{Code:"MT",Name:RNTranslate("Malta")},{Code:"MH",Name:RNTranslate("Marshall Islands")},{Code:"MQ",Name:RNTranslate("Martinique")},{Code:"MR",Name:RNTranslate("Mauritania")},{Code:"MU",Name:RNTranslate("Mauritius")},{Code:"YT",Name:RNTranslate("Mayotte")},{Code:"MX",Name:RNTranslate("Mexico")},{Code:"FM",Name:RNTranslate("Micronesia")},{Code:"MD",Name:RNTranslate("Moldova")},{Code:"MC",Name:RNTranslate("Monaco")},{Code:"MN",Name:RNTranslate("Mongolia")},{Code:"ME",Name:RNTranslate("Montenegro")},{Code:"MS",Name:RNTranslate("Montserrat")},{Code:"MA",Name:RNTranslate("Morocco")},{Code:"MZ",Name:RNTranslate("Mozambique")},{Code:"MM",Name:RNTranslate("Myanmar (Burma)")},{Code:"NA",Name:RNTranslate("Namibia")},{Code:"NR",Name:RNTranslate("Nauru")},{Code:"NP",Name:RNTranslate("Nepal")},{Code:"NL",Name:RNTranslate("Netherlands")},{Code:"NC",Name:RNTranslate("New Caledonia")},{Code:"NZ",Name:RNTranslate("New Zealand")},{Code:"NI",Name:RNTranslate("Nicaragua")},{Code:"NE",Name:RNTranslate("Niger")},{Code:"NG",Name:RNTranslate("Nigeria")},{Code:"NU",Name:RNTranslate("Niue")},{Code:"NF",Name:RNTranslate("Norfolk Island")},{Code:"KP",Name:RNTranslate("North Korea")},{Code:"MK",Name:RNTranslate("North Macedonia")},{Code:"MP",Name:RNTranslate("Northern Mariana Islands")},{Code:"NO",Name:RNTranslate("Norway")},{Code:"OM",Name:RNTranslate("Oman")},{Code:"PK",Name:RNTranslate("Pakistan")},{Code:"PW",Name:RNTranslate("Palau")},{Code:"PS",Name:RNTranslate("Palestine")},{Code:"PA",Name:RNTranslate("Panama")},{Code:"PG",Name:RNTranslate("Papua New Guinea")},{Code:"PY",Name:RNTranslate("Paraguay")},{Code:"PE",Name:RNTranslate("Peru")},{Code:"PH",Name:RNTranslate("Philippines")},{Code:"PN",Name:RNTranslate("Pitcairn Islands")},{Code:"PL",Name:RNTranslate("Poland")},{Code:"PT",Name:RNTranslate("Portugal")},{Code:"PR",Name:RNTranslate("Puerto Rico")},{Code:"QA",Name:RNTranslate("Qatar")},{Code:"RO",Name:RNTranslate("Romania")},{Code:"RU",Name:RNTranslate("Russia")},{Code:"RW",Name:RNTranslate("Rwanda")},{Code:"RE",Name:RNTranslate("Réunion")},{Code:"WS",Name:RNTranslate("Samoa")},{Code:"SM",Name:RNTranslate("San Marino")},{Code:"SA",Name:RNTranslate("Saudi Arabia")},{Code:"SN",Name:RNTranslate("Senegal")},{Code:"RS",Name:RNTranslate("Serbia")},{Code:"SC",Name:RNTranslate("Seychelles")},{Code:"SL",Name:RNTranslate("Sierra Leone")},{Code:"SG",Name:RNTranslate("Singapore")},{Code:"SX",Name:RNTranslate("Sint Maarten")},{Code:"SK",Name:RNTranslate("Slovakia")},{Code:"SI",Name:RNTranslate("Slovenia")},{Code:"SB",Name:RNTranslate("Solomon Islands")},{Code:"SO",Name:RNTranslate("Somalia")},{Code:"ZA",Name:RNTranslate("South Africa")},{Code:"GS",Name:RNTranslate("South Georgia & South Sandwich Islands")},{Code:"KR",Name:RNTranslate("South Korea")},{Code:"SS",Name:RNTranslate("South Sudan")},{Code:"ES",Name:RNTranslate("Spain")},{Code:"LK",Name:RNTranslate("Sri Lanka")},{Code:"BL",Name:RNTranslate("St. Barthélemy")},{Code:"SH",Name:RNTranslate("St. Helena")},{Code:"KN",Name:RNTranslate("St. Kitts & Nevis")},{Code:"LC",Name:RNTranslate("St. Lucia")},{Code:"MF",Name:RNTranslate("St. Martin")},{Code:"PM",Name:RNTranslate("St. Pierre & Miquelon")},{Code:"VC",Name:RNTranslate("St. Vincent & Grenadines")},{Code:"SD",Name:RNTranslate("Sudan")},{Code:"SR",Name:RNTranslate("Suriname")},{Code:"SJ",Name:RNTranslate("Svalbard & Jan Mayen")},{Code:"SE",Name:RNTranslate("Sweden")},{Code:"CH",Name:RNTranslate("Switzerland")},{Code:"SY",Name:RNTranslate("Syria")},{Code:"ST",Name:RNTranslate("São Tomé & Príncipe")},{Code:"TW",Name:RNTranslate("Taiwan")},{Code:"TJ",Name:RNTranslate("Tajikistan")},{Code:"TZ",Name:RNTranslate("Tanzania")},{Code:"TH",Name:RNTranslate("Thailand")},{Code:"TL",Name:RNTranslate("Timor-Leste")},{Code:"TG",Name:RNTranslate("Togo")},{Code:"TK",Name:RNTranslate("Tokelau")},{Code:"TO",Name:RNTranslate("Tonga")},{Code:"TT",Name:RNTranslate("Trinidad & Tobago")},{Code:"TA",Name:RNTranslate("Tristan da Cunha")},{Code:"TN",Name:RNTranslate("Tunisia")},{Code:"TR",Name:RNTranslate("Turkey")},{Code:"TM",Name:RNTranslate("Turkmenistan")},{Code:"TC",Name:RNTranslate("Turks & Caicos Islands")},{Code:"TV",Name:RNTranslate("Tuvalu")},{Code:"UM",Name:RNTranslate("U.S. Outlying Islands")},{Code:"VI",Name:RNTranslate("U.S. Virgin Islands")},{Code:"UG",Name:RNTranslate("Uganda")},{Code:"UA",Name:RNTranslate("Ukraine")},{Code:"AE",Name:RNTranslate("United Arab Emirates")},{Code:"GB",Name:RNTranslate("United Kingdom")},{Code:"US",Name:RNTranslate("United States")},{Code:"UY",Name:RNTranslate("Uruguay")},{Code:"UZ",Name:RNTranslate("Uzbekistan")},{Code:"VU",Name:RNTranslate("Vanuatu")},{Code:"VA",Name:RNTranslate("Vatican City")},{Code:"VE",Name:RNTranslate("Venezuela")},{Code:"VN",Name:RNTranslate("Vietnam")},{Code:"WF",Name:RNTranslate("Wallis & Futuna")},{Code:"EH",Name:RNTranslate("Western Sahara")},{Code:"YE",Name:RNTranslate("Yemen")},{Code:"ZM",Name:RNTranslate("Zambia")},{Code:"ZW",Name:RNTranslate("Zimbabwe")},{Code:"AX",Name:RNTranslate("Åland Islands")}];i.customElement("rn-google-maps-address")(class extends R.LitElementBase{componentDidMount(){this.Model.OnRefresh.Subscribe(this,(()=>this.forceUpdate()))}componentWillUnmount(){this.Model.OnRefresh.Unsubscribe(this)}render(){var e;let a=this.Model.Parent.Options;this.Model.Options;let s=this.Model,n=null!==(e=a.MarkerLabels[this.Index])&&void 0!==e?e:"";return t.html` <div class='rncontrol'> ${u.rnIf(""!=n.trim()&&t.html` <label style="font-weight: bold">${n}</label> `)} <div class='rnrow' style="display: flex"> <div class='rncolsm1'> <div class='rednaoLabel'> <label style={{fontWeight: 'bold'}}>${a.Address1Label}</label> </div> <input @keypress=${e=>{"Enter"===e.key&&e.preventDefault()}} type='text' ${h.ref((e=>this.AddressRendered(e)))} .value=${p.live(s.Options.Address.StreetAddress1)} @change=${e=>{s.Options.Address.StreetAddress1=e.target.value,this.Model.Refresh()}}/> </div> </div> ${u.rnIf(a.ShowAddress2&&t.html` <div class='rnrow' style="display: flex"> <div class='rncolsm1'> <div class='rednaoLabel'> <label style={{fontWeight: 'bold'}}>${a.Address2Label}</label> </div> <input @keypress=${e=>{"Enter"===e.key&&e.preventDefault()}} type='text' .value=${p.live(s.Options.Address.StreetAddress2)} @change=${e=>{s.Options.Address.StreetAddress2=e.target.value,this.Model.Refresh()}}/> </div> </div> `)} <div class='rnrow' style="display: flex"> ${u.rnIf(a.ShowCity&&t.html` <div class=${"rncolsm"+(a.ShowState?2:1)}> <div class='rednaoLabel'> <label style="font-weight: bold">${a.CityLabel}</label> </div> <input @keypress=${e=>{"Enter"===e.key&&e.preventDefault()}} type='text' .value=${p.live(s.Options.Address.City)} @change=${e=>{s.Options.Address.City=e.target.value,this.Model.Refresh()}}/> </div> `)} ${u.rnIf(a.ShowState&&t.html` <div class=${"rncolsm"+(a.ShowCity?2:1)}> <div class='rednaoLabel'> <label style="font-weight: bold">${a.StateLabel}</label> </div> <input @keypress=${e=>{"Enter"===e.key&&e.preventDefault()}} type='text' .value=${p.live(s.Options.Address.State)} @change=${e=>{s.Options.Address.State=e.target.value,s.Refresh()}}/> </div> `)} </div> <div class='rnrow' style="display: flex"> ${u.rnIf(a.ShowZip&&t.html` <div class=${"rncolsm"+(a.ShowCountry?2:1)}> <div class='rednaoLabel'> <label style="font-weight: bold">${a.ZipLabel}</label> </div> <input @keypress=${e=>{"Enter"===e.key&&e.preventDefault()}} type='text' .value=${p.live(s.Options.Address.Zip)} @change=${e=>{s.Options.Address.Zip=e.target.value,this.Model.Refresh()}}/> </div> `)} ${u.rnIf(a.ShowCountry&&t.html` <div class=${"rncolsm"+(a.ShowZip?2:1)}> <div class='rednaoLabel'> <label style="font-weight: bold">${a.CountryLabel}</label> </div> <select .value=${p.live(s.Options.Address.CountryShort)} @change=${e=>{var a;s.Options.Address.CountryShort=e.target.value,s.Options.Address.CountryLong=null===(a=K.find((e=>e.Code==s.Options.Address.CountryShort)))||void 0===a?void 0:a.Name,this.Model.Refresh()}}> ${K.map((e=>t.html` <option ?selected="${e.Code==this.Model.Options.Address.CountryShort}" .value=${e.Code}>${e.Name}</option> `))} </select> </div> `)} </div> </div> `}AddressRendered(e){if(e!=this.AddressField&&null!=e){this.AddressField=e;let a={};this.Model.Parent.Options.RestrictedCountries.length>0&&(a.componentRestrictions={country:this.Model.Parent.Options.RestrictedCountries}),this.Model.AutoComplete=new window.google.maps.places.Autocomplete(this.AddressField,a),this.Model.AutoComplete.addListener("place_changed",(e=>{this.Model.UpdateAddress();let a=this.Model.AutoComplete.getPlace(),t=new window.google.maps.LatLng(a.geometry.location.lat(),a.geometry.location.lng());var s,n;(this.Model.Parent.SetMarker(this.Model,t,!0),null!=this.Model.Parent.Map&&null!=a)&&(a.geometry.viewport?this.Model.Parent.Map.fitBounds(a.geometry.viewport):(this.Model.Parent.Map.setCenter(a.geometry.location),this.Model.Parent.Map.setZoom(80)),null===(s=this.Model.Marker)||void 0===s||s.setPosition(a.geometry.location),null===(n=this.Model.Marker)||void 0===n||n.setVisible(!0))}))}}}),k=i.customElement("rn-google-maps-address-control"),E=i.query(".mapContainer"),k((D=class extends R.LitElementBase{constructor(){super(),babelHelpers.initializerDefineProperty(this,"MapContainer",F,this),this.ThrottleId=0}render(){return this.Model,this.Model.Options,t.html` <div> ${this.Model.Markers.map(((e,a)=>t.html` <rn-google-maps-address .Model=${e} .Index=${a}/> `))} ${u.rnIf(this.Model.Options.ShowMap&&t.html`<div class="mapContainer" style="width: 100%;height: 200px"/>`)} </div> `}firstUpdated(e){super.firstUpdated(e),this.Model.Map=null,this.Model.InitializeMarkers(),this.Model.Options.ShowMap&&(this.Model.Map=new window.google.maps.Map(this.MapContainer,{center:{lat:-33.8688,lng:151.2195},zoom:13}),window.google.maps.event.addListener(this.Model.Map,"click",(e=>{this.Model.SetLastMarket(e.latLng)})),this.Model.Map.addListener("tilesloaded",(()=>{this.Model.SaveCanvas(null)})),this.Model.Map.addListener("idle",(()=>{this.Model.SaveCanvas(null)})))}},F=babelHelpers.applyDecoratedDescriptor(D.prototype,"MapContainer",[E],{configurable:!0,enumerable:!0,writable:!0,initializer:null}),D)),e.GoogleMapsFieldModel=b,e.GoogleMapsField=G,e.GoogleMapsFieldOptions=B,e.GoogleMapsRunnableComparator=f,T.EventManager.Subscribe("GetFieldOptions",(e=>{if(e==m.FieldTypeEnum.GoogleMaps)return new B})),T.EventManager.Subscribe("GetFieldModel",(e=>{if(e.Options.Type==m.FieldTypeEnum.GoogleMaps)return new b(e.Options,e.Parent)}));var V=[{Code:"AF",Name:RNTranslate("Afghanistan")},{Code:"AL",Name:RNTranslate("Albania")},{Code:"DZ",Name:RNTranslate("Algeria")},{Code:"AS",Name:RNTranslate("American Samoa")},{Code:"AD",Name:RNTranslate("Andorra")},{Code:"AO",Name:RNTranslate("Angola")},{Code:"AI",Name:RNTranslate("Anguilla")},{Code:"AQ",Name:RNTranslate("Antarctica")},{Code:"AG",Name:RNTranslate("Antigua & Barbuda")},{Code:"AR",Name:RNTranslate("Argentina")},{Code:"AM",Name:RNTranslate("Armenia")},{Code:"AW",Name:RNTranslate("Aruba")},{Code:"AC",Name:RNTranslate("Ascension Island")},{Code:"AU",Name:RNTranslate("Australia")},{Code:"AT",Name:RNTranslate("Austria")},{Code:"AZ",Name:RNTranslate("Azerbaijan")},{Code:"BS",Name:RNTranslate("Bahamas")},{Code:"BH",Name:RNTranslate("Bahrain")},{Code:"BD",Name:RNTranslate("Bangladesh")},{Code:"BB",Name:RNTranslate("Barbados")},{Code:"BY",Name:RNTranslate("Belarus")},{Code:"BE",Name:RNTranslate("Belgium")},{Code:"BZ",Name:RNTranslate("Belize")},{Code:"BJ",Name:RNTranslate("Benin")},{Code:"BM",Name:RNTranslate("Bermuda")},{Code:"BT",Name:RNTranslate("Bhutan")},{Code:"BO",Name:RNTranslate("Bolivia")},{Code:"BA",Name:RNTranslate("Bosnia & Herzegovina")},{Code:"BW",Name:RNTranslate("Botswana")},{Code:"BV",Name:RNTranslate("Bouvet Island")},{Code:"BR",Name:RNTranslate("Brazil")},{Code:"IO",Name:RNTranslate("British Indian Ocean Territory")},{Code:"VG",Name:RNTranslate("British Virgin Islands")},{Code:"BN",Name:RNTranslate("Brunei")},{Code:"BG",Name:RNTranslate("Bulgaria")},{Code:"BF",Name:RNTranslate("Burkina Faso")},{Code:"BI",Name:RNTranslate("Burundi")},{Code:"KH",Name:RNTranslate("Cambodia")},{Code:"CM",Name:RNTranslate("Cameroon")},{Code:"CA",Name:RNTranslate("Canada")},{Code:"IC",Name:RNTranslate("Canary Islands")},{Code:"CV",Name:RNTranslate("Cape Verde")},{Code:"BQ",Name:RNTranslate("Caribbean Netherlands")},{Code:"KY",Name:RNTranslate("Cayman Islands")},{Code:"CF",Name:RNTranslate("Central African Republic")},{Code:"EA",Name:RNTranslate("Ceuta & Melilla")},{Code:"TD",Name:RNTranslate("Chad")},{Code:"CL",Name:RNTranslate("Chile")},{Code:"CN",Name:RNTranslate("China")},{Code:"CX",Name:RNTranslate("Christmas Island")},{Code:"CP",Name:RNTranslate("Clipperton Island")},{Code:"CC",Name:RNTranslate("Cocos (Keeling) Islands")},{Code:"CO",Name:RNTranslate("Colombia")},{Code:"KM",Name:RNTranslate("Comoros")},{Code:"CG",Name:RNTranslate("Congo - Brazzaville")},{Code:"CD",Name:RNTranslate("Congo - Kinshasa")},{Code:"CK",Name:RNTranslate("Cook Islands")},{Code:"CR",Name:RNTranslate("Costa Rica")},{Code:"HR",Name:RNTranslate("Croatia")},{Code:"CU",Name:RNTranslate("Cuba")},{Code:"CW",Name:RNTranslate("Curaçao")},{Code:"CY",Name:RNTranslate("Cyprus")},{Code:"CZ",Name:RNTranslate("Czechia")},{Code:"CI",Name:RNTranslate("Côte d’Ivoire")},{Code:"DK",Name:RNTranslate("Denmark")},{Code:"DG",Name:RNTranslate("Diego Garcia")},{Code:"DJ",Name:RNTranslate("Djibouti")},{Code:"DM",Name:RNTranslate("Dominica")},{Code:"DO",Name:RNTranslate("Dominican Republic")},{Code:"EC",Name:RNTranslate("Ecuador")},{Code:"EG",Name:RNTranslate("Egypt")},{Code:"SV",Name:RNTranslate("El Salvador")},{Code:"GQ",Name:RNTranslate("Equatorial Guinea")},{Code:"ER",Name:RNTranslate("Eritrea")},{Code:"EE",Name:RNTranslate("Estonia")},{Code:"SZ",Name:RNTranslate("Eswatini")},{Code:"ET",Name:RNTranslate("Ethiopia")},{Code:"FK",Name:RNTranslate("Falkland Islands (Islas Malvinas)")},{Code:"FO",Name:RNTranslate("Faroe Islands")},{Code:"FJ",Name:RNTranslate("Fiji")},{Code:"FI",Name:RNTranslate("Finland")},{Code:"FR",Name:RNTranslate("France")},{Code:"GF",Name:RNTranslate("French Guiana")},{Code:"PF",Name:RNTranslate("French Polynesia")},{Code:"TF",Name:RNTranslate("French Southern Territories")},{Code:"GA",Name:RNTranslate("Gabon")},{Code:"GM",Name:RNTranslate("Gambia")},{Code:"GE",Name:RNTranslate("Georgia")},{Code:"DE",Name:RNTranslate("Germany")},{Code:"GH",Name:RNTranslate("Ghana")},{Code:"GI",Name:RNTranslate("Gibraltar")},{Code:"GR",Name:RNTranslate("Greece")},{Code:"GL",Name:RNTranslate("Greenland")},{Code:"GD",Name:RNTranslate("Grenada")},{Code:"GP",Name:RNTranslate("Guadeloupe")},{Code:"GU",Name:RNTranslate("Guam")},{Code:"GT",Name:RNTranslate("Guatemala")},{Code:"GG",Name:RNTranslate("Guernsey")},{Code:"GN",Name:RNTranslate("Guinea")},{Code:"GW",Name:RNTranslate("Guinea-Bissau")},{Code:"GY",Name:RNTranslate("Guyana")},{Code:"HT",Name:RNTranslate("Haiti")},{Code:"HM",Name:RNTranslate("Heard & McDonald Islands")},{Code:"HN",Name:RNTranslate("Honduras")},{Code:"HK",Name:RNTranslate("Hong Kong")},{Code:"HU",Name:RNTranslate("Hungary")},{Code:"IS",Name:RNTranslate("Iceland")},{Code:"IN",Name:RNTranslate("India")},{Code:"ID",Name:RNTranslate("Indonesia")},{Code:"IR",Name:RNTranslate("Iran")},{Code:"IQ",Name:RNTranslate("Iraq")},{Code:"IE",Name:RNTranslate("Ireland")},{Code:"IM",Name:RNTranslate("Isle of Man")},{Code:"IL",Name:RNTranslate("Israel")},{Code:"IT",Name:RNTranslate("Italy")},{Code:"JM",Name:RNTranslate("Jamaica")},{Code:"JP",Name:RNTranslate("Japan")},{Code:"JE",Name:RNTranslate("Jersey")},{Code:"JO",Name:RNTranslate("Jordan")},{Code:"KZ",Name:RNTranslate("Kazakhstan")},{Code:"KE",Name:RNTranslate("Kenya")},{Code:"KI",Name:RNTranslate("Kiribati")},{Code:"XK",Name:RNTranslate("Kosovo")},{Code:"KW",Name:RNTranslate("Kuwait")},{Code:"KG",Name:RNTranslate("Kyrgyzstan")},{Code:"LA",Name:RNTranslate("Laos")},{Code:"LV",Name:RNTranslate("Latvia")},{Code:"LB",Name:RNTranslate("Lebanon")},{Code:"LS",Name:RNTranslate("Lesotho")},{Code:"LR",Name:RNTranslate("Liberia")},{Code:"LY",Name:RNTranslate("Libya")},{Code:"LI",Name:RNTranslate("Liechtenstein")},{Code:"LT",Name:RNTranslate("Lithuania")},{Code:"LU",Name:RNTranslate("Luxembourg")},{Code:"MO",Name:RNTranslate("Macao")},{Code:"MG",Name:RNTranslate("Madagascar")},{Code:"MW",Name:RNTranslate("Malawi")},{Code:"MY",Name:RNTranslate("Malaysia")},{Code:"MV",Name:RNTranslate("Maldives")},{Code:"ML",Name:RNTranslate("Mali")},{Code:"MT",Name:RNTranslate("Malta")},{Code:"MH",Name:RNTranslate("Marshall Islands")},{Code:"MQ",Name:RNTranslate("Martinique")},{Code:"MR",Name:RNTranslate("Mauritania")},{Code:"MU",Name:RNTranslate("Mauritius")},{Code:"YT",Name:RNTranslate("Mayotte")},{Code:"MX",Name:RNTranslate("Mexico")},{Code:"FM",Name:RNTranslate("Micronesia")},{Code:"MD",Name:RNTranslate("Moldova")},{Code:"MC",Name:RNTranslate("Monaco")},{Code:"MN",Name:RNTranslate("Mongolia")},{Code:"ME",Name:RNTranslate("Montenegro")},{Code:"MS",Name:RNTranslate("Montserrat")},{Code:"MA",Name:RNTranslate("Morocco")},{Code:"MZ",Name:RNTranslate("Mozambique")},{Code:"MM",Name:RNTranslate("Myanmar (Burma)")},{Code:"NA",Name:RNTranslate("Namibia")},{Code:"NR",Name:RNTranslate("Nauru")},{Code:"NP",Name:RNTranslate("Nepal")},{Code:"NL",Name:RNTranslate("Netherlands")},{Code:"NC",Name:RNTranslate("New Caledonia")},{Code:"NZ",Name:RNTranslate("New Zealand")},{Code:"NI",Name:RNTranslate("Nicaragua")},{Code:"NE",Name:RNTranslate("Niger")},{Code:"NG",Name:RNTranslate("Nigeria")},{Code:"NU",Name:RNTranslate("Niue")},{Code:"NF",Name:RNTranslate("Norfolk Island")},{Code:"KP",Name:RNTranslate("North Korea")},{Code:"MK",Name:RNTranslate("North Macedonia")},{Code:"MP",Name:RNTranslate("Northern Mariana Islands")},{Code:"NO",Name:RNTranslate("Norway")},{Code:"OM",Name:RNTranslate("Oman")},{Code:"PK",Name:RNTranslate("Pakistan")},{Code:"PW",Name:RNTranslate("Palau")},{Code:"PS",Name:RNTranslate("Palestine")},{Code:"PA",Name:RNTranslate("Panama")},{Code:"PG",Name:RNTranslate("Papua New Guinea")},{Code:"PY",Name:RNTranslate("Paraguay")},{Code:"PE",Name:RNTranslate("Peru")},{Code:"PH",Name:RNTranslate("Philippines")},{Code:"PN",Name:RNTranslate("Pitcairn Islands")},{Code:"PL",Name:RNTranslate("Poland")},{Code:"PT",Name:RNTranslate("Portugal")},{Code:"PR",Name:RNTranslate("Puerto Rico")},{Code:"QA",Name:RNTranslate("Qatar")},{Code:"RO",Name:RNTranslate("Romania")},{Code:"RU",Name:RNTranslate("Russia")},{Code:"RW",Name:RNTranslate("Rwanda")},{Code:"RE",Name:RNTranslate("Réunion")},{Code:"WS",Name:RNTranslate("Samoa")},{Code:"SM",Name:RNTranslate("San Marino")},{Code:"SA",Name:RNTranslate("Saudi Arabia")},{Code:"SN",Name:RNTranslate("Senegal")},{Code:"RS",Name:RNTranslate("Serbia")},{Code:"SC",Name:RNTranslate("Seychelles")},{Code:"SL",Name:RNTranslate("Sierra Leone")},{Code:"SG",Name:RNTranslate("Singapore")},{Code:"SX",Name:RNTranslate("Sint Maarten")},{Code:"SK",Name:RNTranslate("Slovakia")},{Code:"SI",Name:RNTranslate("Slovenia")},{Code:"SB",Name:RNTranslate("Solomon Islands")},{Code:"SO",Name:RNTranslate("Somalia")},{Code:"ZA",Name:RNTranslate("South Africa")},{Code:"GS",Name:RNTranslate("South Georgia & South Sandwich Islands")},{Code:"KR",Name:RNTranslate("South Korea")},{Code:"SS",Name:RNTranslate("South Sudan")},{Code:"ES",Name:RNTranslate("Spain")},{Code:"LK",Name:RNTranslate("Sri Lanka")},{Code:"BL",Name:RNTranslate("St. Barthélemy")},{Code:"SH",Name:RNTranslate("St. Helena")},{Code:"KN",Name:RNTranslate("St. Kitts & Nevis")},{Code:"LC",Name:RNTranslate("St. Lucia")},{Code:"MF",Name:RNTranslate("St. Martin")},{Code:"PM",Name:RNTranslate("St. Pierre & Miquelon")},{Code:"VC",Name:RNTranslate("St. Vincent & Grenadines")},{Code:"SD",Name:RNTranslate("Sudan")},{Code:"SR",Name:RNTranslate("Suriname")},{Code:"SJ",Name:RNTranslate("Svalbard & Jan Mayen")},{Code:"SE",Name:RNTranslate("Sweden")},{Code:"CH",Name:RNTranslate("Switzerland")},{Code:"SY",Name:RNTranslate("Syria")},{Code:"ST",Name:RNTranslate("São Tomé & Príncipe")},{Code:"TW",Name:RNTranslate("Taiwan")},{Code:"TJ",Name:RNTranslate("Tajikistan")},{Code:"TZ",Name:RNTranslate("Tanzania")},{Code:"TH",Name:RNTranslate("Thailand")},{Code:"TL",Name:RNTranslate("Timor-Leste")},{Code:"TG",Name:RNTranslate("Togo")},{Code:"TK",Name:RNTranslate("Tokelau")},{Code:"TO",Name:RNTranslate("Tonga")},{Code:"TT",Name:RNTranslate("Trinidad & Tobago")},{Code:"TA",Name:RNTranslate("Tristan da Cunha")},{Code:"TN",Name:RNTranslate("Tunisia")},{Code:"TR",Name:RNTranslate("Turkey")},{Code:"TM",Name:RNTranslate("Turkmenistan")},{Code:"TC",Name:RNTranslate("Turks & Caicos Islands")},{Code:"TV",Name:RNTranslate("Tuvalu")},{Code:"UM",Name:RNTranslate("U.S. Outlying Islands")},{Code:"VI",Name:RNTranslate("U.S. Virgin Islands")},{Code:"UG",Name:RNTranslate("Uganda")},{Code:"UA",Name:RNTranslate("Ukraine")},{Code:"AE",Name:RNTranslate("United Arab Emirates")},{Code:"GB",Name:RNTranslate("United Kingdom")},{Code:"US",Name:RNTranslate("United States")},{Code:"UY",Name:RNTranslate("Uruguay")},{Code:"UZ",Name:RNTranslate("Uzbekistan")},{Code:"VU",Name:RNTranslate("Vanuatu")},{Code:"VA",Name:RNTranslate("Vatican City")},{Code:"VE",Name:RNTranslate("Venezuela")},{Code:"VN",Name:RNTranslate("Vietnam")},{Code:"WF",Name:RNTranslate("Wallis & Futuna")},{Code:"EH",Name:RNTranslate("Western Sahara")},{Code:"YE",Name:RNTranslate("Yemen")},{Code:"ZM",Name:RNTranslate("Zambia")},{Code:"ZW",Name:RNTranslate("Zimbabwe")},{Code:"AX",Name:RNTranslate("Åland Islands")}];e.CountryDictionary=V,Object.defineProperty(e,"__esModule",{value:!0})}));