<template>
  <div>
    <!-- <a :href="slug(job.slug)" class="task-listing">
      <div class="task-listing-details">
        <div class="task-listing-description">
          <h3 class="task-listing-title">Food Delviery Mobile App</h3>
          <ul class="task-icons">
            <li>
              <i class="icon-material-outline-location-on"></i> San Francisco
            </li>
            <li>
              <i class="icon-material-outline-access-time"></i> 2 minutes ago
            </li>
          </ul>
          <p
            class="task-listing-text"
          >Leverage agile frameworks to provide a robust synopsis for high level overviews. Iterative approaches to corporate strategy foster.</p>
          <div class="task-tags">
            <span>iOS</span>
            <span>Android</span>
            <span>mobile apps</span>
            <span>design</span>
          </div>
        </div>
      </div>

      <div class="task-listing-bid">
        <div class="task-listing-bid-inner">
          <div class="task-offers">
            <strong>$1,000 - $2,500</strong>
            <span>Fixed Price</span>
          </div>
          <span class="button button-sliding-icon ripple-effect">
            Bid Now
            <i class="icon-material-outline-arrow-right-alt"></i>
          </span>
        </div>
      </div>
    </a> -->
  </div>
</template>

<script>
export default {
  mounted() {
    this.getResults();
  },

  methods: {
    slug(slug) {
      return "jobs/" + slug;
    },

    getResults(page = 1) {
      axios.get("jobs/?page=" + page).then(response => {
        this.jobs = response.data;
      });
    }
  }
};
</script>
