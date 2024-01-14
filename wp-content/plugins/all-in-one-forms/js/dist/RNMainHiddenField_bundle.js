rndefine("#RNMainHiddenField",["#RNMainCore/EventManager","#RNMainFormBuilderCore/FieldBase.Options","lit","#RNMainFormBuilderCore/FieldWithPrice.Model","lit/decorators","#RNMainFormBuilderCore/FieldBase","#RNMainFormBuilderCore/FieldWithPrice","#RNMainFormBuilderCore/FieldWithPrice.Options"],(function(e,i,t,r,l,n,d,s){"use strict";class a extends r.FieldWithPriceModel{constructor(...e){super(...e),this.ShowField=!1}GetValue(){return this.GetIsVisible()?this.Value:""}SetShowField(e){return this.ShowField=e,this.Refresh(),this}InternalSerialize(e){super.InternalSerialize(e),e.Value=this.GetValue()}get IsHiddenTypeOfField(){return!0}get IsInvisibleTypeOfField(){return!0}GetDynamicFieldNames(){return["HiddenField"]}GetStoresInformation(){return!0}InitializeStartingValues(){this.Value=this.GetPreviousDataProperty("Value",this.Options.Value)}GetText(){return this.GetIsVisible()?this.Value:""}InternalToText(){return this.Value}SetText(e){this.Value=e,this.FireValueChanged()}render(){return t.html`<rn-hidden-field .model="${this}"></rn-hidden-field>`}}var u;let o=l.customElement("rn-hidden-field")(u=class extends d.FieldWithPrice{static get properties(){return n.FieldBase.properties}GetLabel(e="",i,t=!1){return this.model.RootFormBuilder.IsDesign?super.GetLabel(e,i):null}SubRender(){return t.html` <input type='${this.model.ShowField?"text":"hidden"}' value=${this.model.Value}/> `}})||u;class h extends s.FieldWithPriceOptions{LoadDefaultValues(){super.LoadDefaultValues(),this.Value="",this.Label="Hidden",this.Type=i.FieldTypeEnum.Hidden}}exports.HiddenFieldModel=a,exports.HiddenField=o,exports.HiddenFieldOptions=h,e.EventManager.Subscribe("GetFieldOptions",(e=>{if(e==i.FieldTypeEnum.Hidden)return new h})),e.EventManager.Subscribe("GetFieldModel",(e=>{if(e.Options.Type==i.FieldTypeEnum.Hidden)return new a(e.Options,e.Parent)}))}));