require 'test_helper'

class GradeLevelsControllerTest < ActionController::TestCase
  setup do
    @grade_level = grade_levels(:one)
  end

  test "should get index" do
    get :index
    assert_response :success
    assert_not_nil assigns(:grade_levels)
  end

  test "should get new" do
    get :new
    assert_response :success
  end

  test "should create grade_level" do
    assert_difference('GradeLevel.count') do
      post :create, grade_level: {  }
    end

    assert_redirected_to grade_level_path(assigns(:grade_level))
  end

  test "should show grade_level" do
    get :show, id: @grade_level
    assert_response :success
  end

  test "should get edit" do
    get :edit, id: @grade_level
    assert_response :success
  end

  test "should update grade_level" do
    patch :update, id: @grade_level, grade_level: {  }
    assert_redirected_to grade_level_path(assigns(:grade_level))
  end

  test "should destroy grade_level" do
    assert_difference('GradeLevel.count', -1) do
      delete :destroy, id: @grade_level
    end

    assert_redirected_to grade_levels_path
  end
end
