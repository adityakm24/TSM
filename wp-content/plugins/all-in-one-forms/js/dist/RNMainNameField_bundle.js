rndefine("#RNMainNameField",["#RNMainCore/EventManager","#RNMainFormBuilderCore/FieldBase.Options","#RNMainFormBuilderCore/FieldWithPrice.Model","#RNMainCore/StoreBase","#RNMainFormBuilderCore/FieldWithPrice.Options","#RNMainFormBuilderCore/FormBuilder.Options","lit","lit/decorators","#RNMainFormBuilderCore/FieldBase","#RNMainFormBuilderCore/IconDirective","#RNMainFormBuilderCore/FieldWithPrice","lit-html/directives/live.js"],(function(e,t,i,s,a,r,l,n,o,m,d,h){"use strict";var u,F,N;let p;!function(e){e.Single="single",e.FirstAndLast="first_and_last"}(p||(p={}));let c=(u=s.StoreDataType(r.IconOptions),F=class extends a.FieldWithPriceOptions{constructor(...e){super(...e),babelHelpers.initializerDefineProperty(this,"Icon",N,this)}LoadDefaultValues(){super.LoadDefaultValues(),this.Label="Name",this.Type=t.FieldTypeEnum.Name,this.Format=p.FirstAndLast,this.FirstNameLabel="First Name",this.LastNameLabel="Last Name",this.FirstNameDefaultText="",this.LastNameDefaultText="",this.FirstNamePlaceholder="",this.LastNamePlaceholder="",this.Icon=(new r.IconOptions).Merge()}},N=babelHelpers.applyDecoratedDescriptor(F.prototype,"Icon",[u],{configurable:!0,enumerable:!0,writable:!0,initializer:null}),F);class I{}class L extends I{GetValue(){return{Name:this.Name,Format:p.Single}}SetFirstName(e){this.Name=e}SetLastName(e){}IsUsed(){return""!=this.Name.trim()}GetText(){return this.Name}GetFirstName(){return this.Name}GetLastName(){return""}InitializeStartingValues(e){this.Name=e.GetPreviousDataProperty("Value,Name",e.Options.FirstNameDefaultText)}}class v extends I{GetValue(){return{FirstName:this.FirstName,LastName:this.LastName,Format:p.FirstAndLast}}SetFirstName(e){this.FirstName=e}SetLastName(e){this.LastName=e}IsUsed(){return""!=this.FirstName.trim()&&""!=this.LastName.trim()}GetText(){let e=this.FirstName.trim();return""!=e&&(e+=" "),e+this.LastName}GetFirstName(){return this.FirstName}GetLastName(){return this.LastName}InitializeStartingValues(e){this.FirstName=e.GetPreviousDataProperty("Value,FirstName",e.Options.FirstNameDefaultText),this.LastName=e.GetPreviousDataProperty("Value,LastName",e.Options.LastNameDefaultText)}}class G extends i.FieldWithPriceModel{constructor(e,t){super(e,t),this.IsFocused=!1,this.Options.Format==p.Single?this.Formatter=new L:this.Formatter=new v}InternalSerialize(e){super.InternalSerialize(e),e.Value=this.GetValue()}GetText(){return this.InternalToText()}GetStoresInformation(){return!0}GetIsUsed(){return!!super.GetIsUsed()&&this.Formatter.IsUsed()}InternalToText(){return this.Formatter.GetText()}GetValue(){return this.GetIsVisible()?this.Formatter.GetValue():null}GetFirstName(){return this.Formatter.GetFirstName()}GetLastName(){return this.Formatter.GetLastName()}InitializeStartingValues(){this.Formatter.InitializeStartingValues(this)}GetDynamicFieldNames(){return["FBName"]}SetFirstName(e){this.Formatter.SetFirstName(e),this.Formatter.IsUsed()&&this.RemoveError("required"),this.FireValueChanged()}SetLastName(e){this.Formatter.SetLastName(e),this.Formatter.IsUsed()&&this.RemoveError("required"),this.FireValueChanged()}render(){return l.html`<rn-name-field .model="${this}"></rn-name-field>`}}var O;let f=n.customElement("rn-name-field")(O=class extends d.FieldWithPrice{static get properties(){return o.FieldBase.properties}SubRender(){return this.model.Options.Format==p.FirstAndLast?l.html` <div class='rnTextFieldInput' style="white-space: nowrap;display: flex"> <div class='rncolsm2'> <div style="position: relative"> <input ${m.IconDirective(this.model.Options.Icon)} ?readOnly=${this.model.IsReadonly} @focus=${()=>{this.model.IsFocused=!0,this.model.Refresh()}} @blur=${()=>{this.model.IsFocused=!1,this.model.Refresh()}} class='rnInputPrice' placeholder=${this.model.Options.FirstNamePlaceholder} style="width: 100%" type='text' .value=${h.live(this.model.GetFirstName())} @input=${e=>this.OnChangeFirstName(e)}/> </div> <span>${this.GetText(this.model.Options,"FirstNameLabel")}</span> </div> <div class='rncolsm2'> <input ?readOnly=${this.model.IsReadonly} @focus=${()=>{this.model.IsFocused=!0,this.model.Refresh()}} @blur=${()=>{this.model.IsFocused=!1,this.model.Refresh()}} class='rnInputPrice' placeholder=${this.model.Options.LastNamePlaceholder} style="width: 100%" type='text' .value=${h.live(this.model.GetLastName())} @input=${e=>this.OnChangeLastName(e)}/> <span>${this.GetText(this.model.Options,"LastNameLabel")}</span> </div> </div>`:l.html` <div class='rnTextFieldInput'> <div style="position: relative"> <input ${m.IconDirective(this.model.Options.Icon)} ?readOnly=${this.model.IsReadonly} @focus=${()=>{this.model.IsFocused=!0,this.model.Refresh()}} @blur=${()=>{this.model.IsFocused=!1,this.model.Refresh()}} class='rnInputPrice' .placeholder=${this.model.Options.FirstNamePlaceholder} style="width: 100%;" type='text' .value=${this.model.GetFirstName()} @input=${e=>this.OnChangeFirstName(e)}/> </div> </div> `}OnChangeFirstName(e){this.model.SetFirstName(e.target.value)}OnChangeLastName(e){this.model.SetLastName(e.target.value)}})||O;exports.NameFieldModel=G,exports.NameField=f,exports.NameFieldOptions=c,exports.NameFormatEnum=p,e.EventManager.Subscribe("GetFieldOptions",(e=>{if(e==t.FieldTypeEnum.Name)return new c})),e.EventManager.Subscribe("GetFieldModel",(e=>{if(e.Options.Type==t.FieldTypeEnum.Name)return new G(e.Options,e.Parent)}))}));
