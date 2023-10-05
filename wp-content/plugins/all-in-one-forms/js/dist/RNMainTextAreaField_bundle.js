rndefine("#RNMainTextAreaField",["#RNMainCore/EventManager","#RNMainFormBuilderCore/FieldBase.Options","#RNMainFormBuilderCore/FieldWithPrice.Model","lit","lit/decorators","#RNMainFormBuilderCore/FieldBase","#RNMainFormBuilderCore/IconDirective","#RNMainFormBuilderCore/FieldWithPrice","lit-html/directives/live.js","#RNMainCore/StoreBase","#RNMainFormBuilderCore/FieldWithPrice.Options","#RNMainFormBuilderCore/FormBuilder.Options","#RNMainFormBuilderCore/CalculatorBase","#RNMainCore/Sanitizer"],(function(e,t,r,i,a,l,s,n,o,u,d,h,c,p){"use strict";class F extends r.FieldWithPriceModel{constructor(e,t){super(e,t),this.IsFocused=!1}GetStoresInformation(){return!0}GetIsUsed(){return!!super.GetIsUsed()&&""!=this.Text.trim()}GetText(){return this.Text}InternalToText(){return this.Text}GetValue(){return this.GetIsVisible()?this.Text:""}InternalSerialize(e){super.InternalSerialize(e),e.Value=this.GetValue()}InitializeStartingValues(){this.Text=this.GetPreviousDataProperty("Value",this.Options.DefaultText)}GetDynamicFieldNames(){return["FBTextArea"]}SetText(e){this.Text=e,""!=this.Text.trim()&&this.RemoveError("required"),this.FireValueChanged()}render(){return i.html`<rn-text-area-field .model="${this}"></rn-text-area-field>`}}var x;let m=a.customElement("rn-text-area-field")(x=class extends n.FieldWithPrice{static get properties(){return l.FieldBase.properties}SubRender(){return i.html` <div style="position: relative;"> <textarea ${s.IconDirective(this.model.Options.Icon)} ?readOnly=${this.model.IsReadonly} @focus=${()=>{this.model.IsFocused=!0,this.model.Refresh()}} @blur=${()=>{this.model.IsFocused=!1,this.model.Refresh()}} class='rnInputPrice' placeholder=${this.model.Options.Placeholder} style="width: 100%;" .value=${o.live(this.model.GetText())} @input=${e=>this.OnChange(e)}></textarea> </div> `}OnChange(e){this.model.SetText(e.target.value)}})||x;var T,g,C;let O=(T=u.StoreDataType(Object),g=class extends d.FieldWithPriceOptions{constructor(...e){super(...e),this.FreeCharOrWords=0,babelHelpers.initializerDefineProperty(this,"Icon",C,this)}LoadDefaultValues(){super.LoadDefaultValues(),this.IgnoreSpaces=!1,this.Type=t.FieldTypeEnum.TextArea,this.Label="Text area",this.Placeholder="",this.DefaultText="",this.FreeCharOrWords=0,this.Icon=(new h.IconOptions).Merge()}},C=babelHelpers.applyDecoratedDescriptor(g.prototype,"Icon",[T],{configurable:!0,enumerable:!0,writable:!0,initializer:null}),g);class P extends c.CalculatorBase{ExecuteCalculation(e){null==e&&(e=this.Field.GetText());let t=e.length;this.Field.Options.IgnoreSpaces&&(t=e.replace(/\s/g,"").length);let r=this.Field.Options.FreeCharOrWords;return r>0&&(t=Math.max(0,t-r)),e.length>0?{Quantity:this.GetQuantity(),RegularPrice:t*p.Sanitizer.SanitizeNumber(this.Field.Options.Price),SalePrice:""}:{RegularPrice:"",SalePrice:"",Quantity:0}}ParseNumber(e){let t=parseFloat(e);return isNaN(t)?0:t}}class S extends c.CalculatorBase{ExecuteCalculation(e){null==e&&(e=this.Field.GetText());let t=e.match(/\S+/g),r=null==t?0:t.length,i=this.Field.Options.FreeCharOrWords;return i>0&&(r=Math.max(0,r-i)),e.length>0?{Quantity:this.GetQuantity(),RegularPrice:r*p.Sanitizer.SanitizeNumber(this.Field.Options.Price),SalePrice:""}:{RegularPrice:"",SalePrice:"",Quantity:0}}ParseNumber(e){let t=parseFloat(e);return isNaN(t)?0:t}}exports.TextAreaFieldModel=F,exports.TextAreaField=m,exports.TextAreaFieldOptions=O,e.EventManager.Subscribe("GetFieldOptions",(e=>{if(e==t.FieldTypeEnum.TextArea)return new O})),e.EventManager.Subscribe("GetFieldModel",(e=>{if(e.Options.Type==t.FieldTypeEnum.TextArea)return new F(e.Options,e.Parent)})),e.EventManager.Subscribe("GetCalculator",(e=>{if("price_per_char"==e)return new P})),e.EventManager.Subscribe("GetCalculator",(e=>{if("price_per_word"==e)return new S}))}));
