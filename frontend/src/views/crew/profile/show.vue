<script setup>
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';
import useCrewProfile from '../../../composables/crew/useCrewProfile.js';
import Title from "../../../services/title";
import useHeroIcon from "../../../assets/heroIcon";
import DefaultButton from "../../../components/buttons/DefaultButton.vue";
import env from '../../../config/env';

const icons = useHeroIcon();

const route = useRoute();
const profileId = route.params.profileId;
const { crewProfile, showCrewProfile, errors } = useCrewProfile();

const { setTitle } = Title();

setTitle('Crew Profile Details');

onMounted(() => {
  showCrewProfile(profileId);
});
</script>

<template>
  <div class="flex items-center justify-between w-full my-3" v-once>
    <h2 class="text-2xl font-semibold text-gray-700 dark-disabled:text-gray-200">Crew Profile Details</h2>
    <default-button :title="'Crew Profile List'" :to="{ name: 'crw.profiles.index' }" :icon="icons.DataBase"></default-button>
  </div>
  <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark-disabled:bg-gray-800">
      <div class="flex md:gap-4">
        <div class="w-full">
          <h2 class="bg-green-600 text-white text-md font-semibold uppercase mb-2 text-center py-2">Crew Profile Information</h2>
          <table class="w-full">
            <thead>
            <tr>
              <td class="!text-center bg-gray-200 font-bold" colspan="2">Personal Info</td>
            </tr>
            </thead>
            <tbody>
              <tr>
                <th class="w-40">Business Unit</th>
                <td><span :class="crewProfile?.business_unit === 'PSML' ? 'text-green-700 bg-green-100' : 'text-orange-700 bg-orange-100'" class="px-2 py-1 font-semibold leading-tight rounded-full">{{ crewProfile?.business_unit }}</span></td>
              </tr>
              <tr>
                <th class="w-40">Full Name</th>
                <td>{{ crewProfile?.full_name }}</td>
              </tr>
              <tr>
                <th class="w-40">Profile Picture</th>
                <td>
                  <template v-if="crewProfile?.picture">
                    <img
                        :src="env.BASE_API_URL + '/' + crewProfile?.picture"
                        alt="Crew Profile Image"
                        class="h-20 w-auto"
                    />
                  </template>
                  <template v-else>
                    <span>Not Available</span>
                  </template>
                </td>
              </tr>
              <tr>
                <th>Recruitment Approval</th>
                <td>
                  [
                  <strong>Applied Date:</strong> {{ crewProfile?.crewRecruitmentApproval?.applied_date }},
                  <strong>Page Title:</strong> {{ crewProfile?.crewRecruitmentApproval?.page_title }},
                  <strong>Total Approved:</strong> {{ crewProfile?.crewRecruitmentApproval?.total_approved }}
                  ]
<!--                  <router-link v-if="crewProfile?.crw_recruitment_approval_id" class="text-blue-600 hover:underline" target="_blank" :to="{ name: 'crw.recruitmentApprovals.edit', params: { recruitmentApprovalId: crewProfile?.crw_recruitment_approval_id }}">Show More</router-link>-->
                </td>
              </tr>
              <tr>
                <th>Hired By</th>
                <td>
                  {{ crewProfile?.hired_by }}
                  <template v-if="crewProfile?.hired_by === 'Agency'">
                    [
                    <strong>Name:</strong> {{ crewProfile?.crewAgency?.agency_name }},
                    <strong>Legal Name:</strong> {{ crewProfile?.crewAgency?.legal_name }},
                    <strong>Phone:</strong> {{ crewProfile?.crewAgency?.phone }}
                    ]
<!--                    <router-link class="text-blue-600 hover:underline" v-if="crewProfile?.agency_id" target="_blank" :to="{ name: 'crw.agencies.edit', params: { agencyId: crewProfile?.agency_id }}">Show More</router-link>-->
                  </template>
                </td>
              </tr>
              <tr>
                <th>Department</th>
                <td>{{ crewProfile?.department_name }}</td>
              </tr>
              <tr>
                <th>Rank</th>
                <td>{{ crewProfile?.crewRank?.name }}</td>
              </tr>
              <tr>
                <th>Attachment</th>
                <td>
                  <a class="text-red-700" target="_blank" :href="env.BASE_API_URL+'/'+crewProfile?.attachment">{{
                      (typeof crewProfile?.attachment === 'string')
                          ? '('+crewProfile?.attachment.split('/').pop()+')'
                          : '----'
                    }}</a>
                </td>
              </tr>
              <tr>
                <th>Others</th>
                <td>
                  <div class="grid grid-cols-2 gap-2">
                    <table class="w-full">
                      <tbody>
                      <tr>
                        <th>First Name</th>
                        <td>{{ crewProfile?.first_name }}</td>
                      </tr>
                      <tr>
                        <th>Last Name</th>
                        <td>{{ crewProfile?.last_name }}</td>
                      </tr>
                      <tr>
                        <th>Father's Name</th>
                        <td>{{ crewProfile?.father_name }}</td>
                      </tr>
                      <tr>
                        <th>Mother's Name</th>
                        <td>{{ crewProfile?.mother_name }}</td>
                      </tr>
                      <tr>
                        <th>Date of Birth</th>
                        <td>{{ crewProfile?.date_of_birth }}</td>
                      </tr>
                      <tr>
                        <th>Gender</th>
                        <td>{{ crewProfile?.gender }}</td>
                      </tr>
                      <tr>
                        <th>Religion</th>
                        <td>{{ crewProfile?.religion }}</td>
                      </tr>
                      <tr>
                        <th>Marital Status</th>
                        <td>{{ crewProfile?.marital_status }}</td>
                      </tr>
                      <tr>
                        <th>Nationality</th>
                        <td>{{ crewProfile?.nationality }}</td>
                      </tr>
                      <tr>
                        <th>NID</th>
                        <td>{{ crewProfile?.nid_no }}</td>
                      </tr>
                      <tr v-if="crewProfile?.passport_no">
                        <th>Passport No & Issue Date</th>
                        <td>{{ crewProfile?.passport_no }}, {{ crewProfile?.passport_issue_date }}</td>
                      </tr>
                      <tr v-if="crewProfile?.blood_group">
                        <th>Blood Group</th>
                        <td>{{ crewProfile?.blood_group }}</td>
                      </tr>
                      <tr v-if="crewProfile?.height">
                        <th>Height (Meters)</th>
                        <td>{{ crewProfile?.height }}</td>
                      </tr>
                      <tr v-if="crewProfile?.weight">
                        <th>Weight (KG)</th>
                        <td>{{ crewProfile?.weight }}</td>
                      </tr>
                      </tbody>
                    </table>
                    <table class="w-full">
                      <tbody>
                      <tr class="bg-gray-300">
                        <td style="text-align: center" class="font-bold" colspan="2">Present Contact Info</td>
                      </tr>
                      <tr>
                        <th>Address</th>
                        <td>{{ crewProfile?.pre_address }}</td>
                      </tr>
                      <tr>
                        <th>City</th>
                        <td>{{ crewProfile?.pre_city }}</td>
                      </tr>
                      <tr>
                        <th>Mobile No</th>
                        <td>{{ crewProfile?.pre_mobile_no }}</td>
                      </tr>
                      <tr>
                        <th>Email</th>
                        <td>{{ crewProfile?.pre_email }}</td>
                      </tr>
                      <tr class="bg-gray-300">
                        <td style="text-align: center" class="font-bold" colspan="2">Permanent Contact Info</td>
                      </tr>
                      <tr>
                        <th>Address</th>
                        <td>{{ crewProfile?.per_address }}</td>
                      </tr>
                      <tr>
                        <th>City</th>
                        <td>{{ crewProfile?.per_city }}</td>
                      </tr>
                      <tr>
                        <th>Mobile No</th>
                        <td>{{ crewProfile?.per_mobile_no }}</td>
                      </tr>
                      <tr>
                        <th>Email</th>
                        <td>{{ crewProfile?.per_email }}</td>
                      </tr>
                      </tbody>
                    </table>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
          <table class="w-full mt-1" id="profileDetailTable">
            <thead>
            <tr>
              <td class="!text-center bg-gray-200 font-bold" colspan="8">Educations</td>
            </tr>
            <tr>
              <th>Sl.</th>
              <th>Exam Title</th>
              <th>Major</th>
              <th>Institute</th>
              <th>Result</th>
              <th>Passing Year</th>
              <th>Duration</th>
              <th>Achievement</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(educationData,index) in crewProfile?.educations" :key="index">
              <td>{{ index + 1 }}</td>
              <td>{{ educationData?.exam_title }}</td>
              <td>{{ educationData?.major }}</td>
              <td>{{ educationData?.institute }}</td>
              <td>{{ educationData?.result }}</td>
              <td>{{ educationData?.passing_year }}</td>
              <td>{{ educationData?.duration }}</td>
              <td>{{ educationData?.achievement }}</td>
            </tr>
            </tbody>
            <tfoot v-if="!crewProfile?.educations?.length">
            <tr>
              <td colspan="8">No data found.</td>
            </tr>
            </tfoot>
          </table>
          <table class="w-full mt-1" id="profileDetailTable">
            <thead>
            <tr>
              <td class="!text-center bg-gray-200 font-bold" colspan="7">Trainings</td>
            </tr>
            <tr>
              <th>Sl.</th>
              <th>Training Title</th>
              <th>Covered Topics</th>
              <th>Training Year</th>
              <th>Institute</th>
              <th>Address</th>
              <th>Duration</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(trainingData,index) in crewProfile?.trainings" :key="index">
              <td>{{ index + 1 }}</td>
              <td>{{ trainingData?.training_title }}</td>
              <td>{{ trainingData?.covered_topic }}</td>
              <td>{{ trainingData?.year }}</td>
              <td>{{ trainingData?.institute }}</td>
              <td>{{ trainingData?.location }}</td>
              <td>{{ trainingData?.duration }}</td>
            </tr>
            </tbody>
            <tfoot v-if="!crewProfile?.trainings?.length">
            <tr>
              <td colspan="7">No data found.</td>
            </tr>
            </tfoot>
          </table>
          <table class="w-full mt-1" id="profileDetailTable">
            <thead>
            <tr>
              <td class="!text-center bg-gray-200 font-bold" colspan="6">Experiences</td>
            </tr>
            <tr>
              <th>Sl.</th>
              <th>Employer Name</th>
              <th>From</th>
              <th>To</th>
              <th>Last Designation</th>
              <th>Reason For Leave</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(experienceData,index) in crewProfile?.experiences" :key="index">
              <td>{{ index + 1 }}</td>
              <td>{{ experienceData?.employer_name }}</td>
              <td>{{ experienceData?.from_date }}</td>
              <td>{{ experienceData?.till_date }}</td>
              <td>{{ experienceData?.last_designation }}</td>
              <td>{{ experienceData?.reason_for_leave }}</td>
            </tr>
            </tbody>
            <tfoot v-if="!crewProfile?.experiences?.length">
            <tr>
              <td colspan="6">No data found.</td>
            </tr>
            </tfoot>
          </table>
          <table class="w-full mt-1" id="profileDetailTable">
            <thead>
            <tr>
              <td class="!text-center bg-gray-200 font-bold" colspan="6">Others</td>
            </tr>
            <tr>
              <th>Sl.</th>
              <th>Language</th>
              <th>Writing</th>
              <th>Reading</th>
              <th>Speaking</th>
              <th>Listening</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(languageData,index) in crewProfile?.languages" :key="index">
              <td>{{ index + 1 }}</td>
              <td>{{ languageData?.language_name }}</td>
              <td>{{ languageData?.writing }}</td>
              <td>{{ languageData?.reading }}</td>
              <td>{{ languageData?.speaking }}</td>
              <td>{{ languageData?.listening }}</td>
            </tr>
            </tbody>
            <tfoot v-if="!crewProfile?.languages?.length">
            <tr>
              <td colspan="6">No data found.</td>
            </tr>
            </tfoot>
          </table>
          <table class="w-full mt-1" id="profileDetailTable">
            <thead>
            <tr>
              <td class="!text-center bg-gray-200 font-bold" colspan="8">References</td>
            </tr>
            <tr>
              <th>Sl.</th>
              <th>Name</th>
              <th>Organization</th>
              <th>Designation</th>
              <th>Address</th>
              <th>Contact No.</th>
              <th>Email</th>
              <th>Relation</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(referenceData,index) in crewProfile?.references" :key="index">
              <td>{{ index + 1 }}</td>
              <td>{{ referenceData?.name }}</td>
              <td>{{ referenceData?.organization }}</td>
              <td>{{ referenceData?.designation }}</td>
              <td>{{ referenceData?.address }}</td>
              <td>{{ referenceData?.contact_personal }}</td>
              <td>{{ referenceData?.email }}</td>
              <td>{{ referenceData?.relation }}</td>
            </tr>
            </tbody>
            <tfoot v-if="!crewProfile?.references?.length">
            <tr>
              <td colspan="8">No data found.</td>
            </tr>
            </tfoot>
          </table>
          <table class="w-full mt-1" id="profileDetailTable">
            <thead>
            <tr>
              <td class="!text-center bg-gray-200 font-bold" colspan="8">Nominees</td>
            </tr>
            <tr>
              <th>Sl.</th>
              <th>Name</th>
              <th>Profession</th>
              <th>Address</th>
              <th>Relationship</th>
              <th>Contact No.</th>
              <th>Email</th>
              <th>Is Relative</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(nomineeData,index) in crewProfile?.nominees" :key="index">
              <td>{{ index + 1 }}</td>
              <td>{{ nomineeData?.name }}</td>
              <td>{{ nomineeData?.profession }}</td>
              <td>{{ nomineeData?.address }}</td>
              <td>{{ nomineeData?.relationship }}</td>
              <td>{{ nomineeData?.contact_no }}</td>
              <td>{{ nomineeData?.email }}</td>
              <td>{{ (nomineeData?.is_relative === "1") ? "Yes" : "No" }}</td>
            </tr>
            </tbody>
            <tfoot v-if="!crewProfile?.nominees?.length">
            <tr>
              <td colspan="8">No data found.</td>
            </tr>
            </tfoot>
          </table>
        </div>
      </div>
  </div>
</template>
<style lang="postcss" scoped>
  th, td, tr {
    @apply text-left border-gray-500
  }

  tfoot td{
    @apply text-center
  }

  #profileDetailTable th{
    text-align: center;
  }
  #profileDetailTable thead tr{
    @apply bg-gray-200
  }

</style>