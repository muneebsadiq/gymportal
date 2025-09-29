<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Membership Plans</h5>
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignPlanModal">Assign Plan</button>
    </div>
    <div class="card-body">
        @if($member->memberMembershipPlans->isEmpty())
            <p>No membership plans assigned.</p>
        @else
        <div class="alert alert-info">Membership plans are now assigned directly when creating or editing a member. Please use the member edit form to change a member's plan.</div>
        @endif
    </div>
</div>

      <form action="{{ route('member_membership_plans.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="assignPlanModalLabel">Assign Membership Plan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="member_id" value="{{ $member->id }}">
          <div class="mb-3">
            <label for="membership_plan_id" class="form-label">Plan</label>
            <select class="form-control" id="membership_plan_id" name="membership_plan_id" required>
              <option value="">Select Plan</option>
              @foreach($plans as $plan)
                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" required>
          </div>
          <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" required>
          </div>
          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
              <option value="active">Active</option>
              <option value="expired">Expired</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Assign Plan</button>
        </div>
      </form>
    </div>
  </div>
</div>
