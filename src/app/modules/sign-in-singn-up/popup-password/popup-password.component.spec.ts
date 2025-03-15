import { ComponentFixture, TestBed } from '@angular/core/testing';

import { PopupPasswordComponent } from './popup-password.component';

describe('PopupPasswordComponent', () => {
  let component: PopupPasswordComponent;
  let fixture: ComponentFixture<PopupPasswordComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [PopupPasswordComponent]
    });
    fixture = TestBed.createComponent(PopupPasswordComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
