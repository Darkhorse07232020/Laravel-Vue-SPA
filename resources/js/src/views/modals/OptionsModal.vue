<template>
  <div>
    <vs-prompt 
      title="Door Options"
      accept-text= "Save"
      cancel-text = "Close"
      button-cancel = "border"
      scroll = true
      @cancel="removeTodo"
      @accept="submitTodo"
      @close="init"
      :active.sync="activePrompt">
      <div>
        <div class="mr-0 mt-0 mb-4 px-6 py-6" style="float: right; border: ridge; ">
          <img :src="`/images/doors/${optionsVal.doorName}/Door ${optionsVal.doorName} Thumbnail ${drawerData.door_code}.png`" alt="No-Image" width = '200' @error="imageLoadError" />
        </div>
        <div>
          <div class="px-3 ml-6 mr-auto" v-if="profiles.insides.length>0">
            <h4>Inside Profile</h4>
          </div>
          <div class="flex flex-wrap px-6 mb-6">
            <div class="px-3 ml-6" v-for="(inside, index) in profiles.insides" :key="index">
              <vs-radio v-model="inside_radio" :vs-value="inside" @change="submitTodo">
                <img :src="`/images/inside/${inside.Code}.png`" @error="imageLoadError" />
              </vs-radio><br>
              <h6>{{inside.Name.trim()}}</h6>
            </div>
          </div>
        </div>

        <div>
          <div class="px-3 ml-6 mr-auto" v-if="profiles.centerpanels.length>0">
            <h4>Center Panels</h4>
          </div>
          <div class="flex flex-wrap px-6 mb-6">
            <div class="px-3 ml-6" v-for="(centerpanel, index) in profiles.centerpanels" :key="index">
              <vs-radio v-model="centerpanel_radio" :vs-value="centerpanel" @change="submitTodo">
                <img :src="`/images/centerpanel/${centerpanel.Code}.png`" @error="imageLoadError" />
              </vs-radio><br>
              <h6>{{centerpanel.Name.trim()}}</h6>
            </div>
          </div>
        </div>

        <div>
          <div class="px-3 ml-6 mr-auto" v-if="profiles.outsides.length>0">
            <h4>Outside Profile</h4>
          </div>
          <div class="flex flex-wrap px-6 mb-6">
            <div class="px-3 ml-6" v-for="(outside, index) in profiles.outsides" :key="index">
              <vs-radio v-model="outside_radio" :vs-value="outside" @change="submitTodo">
                <img :src="`/images/outside/${outside.Code}.png`" @error="imageLoadError" />
              </vs-radio><br>
              <h6>{{outside.Name.trim()}}</h6>
            </div>
          </div>
        </div>

        <div>
          <div class="px-3 ml-6 mr-auto" v-if="profiles.stilerails.length>0">
            <h4>Stiles and Rails</h4>
          </div>
          <div class="flex flex-wrap px-6 mb-6">
            <div class="px-3 ml-6" v-for="(stilerail, index) in profiles.stilerails" :key="index">
              <vs-radio v-model="stilerail_radio" :vs-value="stilerail" @change="submitTodo">
                <img :src="`/images/stilerail/${stilerail.Code}.png`" @error="imageLoadError" />
              </vs-radio><br>
              <h6>{{stilerail.Name.trim()}}</h6>
            </div>
          </div>
        </div>

        <div>
          <div class="px-3 ml-6 mr-auto" v-if="profiles.hardwares.length>0">
            <h4>Handle / Pulls</h4>
          </div>
          <div class="flex flex-wrap px-6 mb-6">
            <div class="px-3 ml-6" v-for="(hardware, index) in profiles.hardwares" :key="index">
              <vs-radio v-model="hardware_radio" :vs-value="hardware" @change="submitTodo">
                <img :src="`/images/hardware/${hardware.Code}.png`" @error="imageLoadError" />
              </vs-radio><br>
              <h6>{{hardware.Name.trim()}}</h6>
            </div>
          </div>
        </div>
      </div>
    </vs-prompt>
  </div>
</template>

<script>
export default {
  props: {
    displayPrompt: {
      type: Boolean,
      required: true
    },
    optionsVal: {
      type: Object,
      required: true
    },
    
  },
  data () {
    return {
      inside_radio: {'Code' :''},
      centerpanel_radio: {'Code' :''},
      outside_radio: {'Code' :''},
      stilerail_radio: {'Code' :''},
      hardware_radio: {'Code' :''},
    }
  },
  computed: {
    activePrompt: {
      get () {
        return this.displayPrompt
      },
      set (value) {
        this.$emit('hideDisplayPrompt', value)
      }
    },
    profiles () {
      return this.$store.state.job.profiles;
    },
    drawerData () {
				return this.$store.state.job.drawerData;
			},
  },
  methods: {
    removeTodo () {
      this.$emit('hideDisplayPrompt')
    },
    init () {
      this.$emit('hideDisplayPrompt')
    },
    submitTodo () {
      var tmp='';
      tmp += (this.inside_radio.Code ? this.inside_radio.Code + ' ' : '');
      tmp += (this.stilerail_radio.Code ? this.stilerail_radio.Code + ' ' : '');
      tmp += (this.centerpanel_radio.Code ? this.centerpanel_radio.Code + ' ' : '');
      tmp += (this.outside_radio.Code ? this.outside_radio.Code : '');

      const payload = {
        door_code: tmp,
        inside_code: this.inside_radio,
        centerpanel_code: this.centerpanel_radio,
        outside_code: this.outside_radio,
        hardware_code: this.hardware_radio,
        stilerail_code: this.stilerail_radio,
      };
      // console.log(payload, this.inside_radio);
      // console.log(this.outside_radio);
      // console.log(this.optionsVal.inside);
      this.$store.dispatch('job/setDrawerdatas', payload);
    },
    imageLoadError(event) {
      event.target.src = "/images/1x1.png";
      // event.target.height = "128";
    },
  },
  created () {

    this.inside_radio = this.optionsVal.inside;

    this.centerpanel_radio = this.optionsVal.centerpanel;

    this.outside_radio = this.optionsVal.outside;

    this.stilerail_radio = this.optionsVal.stile;

    this.hardware_radio = this.optionsVal.hardware;

  }
}
</script>

<style lang="stylus">
.con-vs-dialog .vs-dialog {
    max-width: 90%;
}
</style>